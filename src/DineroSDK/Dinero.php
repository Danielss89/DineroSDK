<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace DineroSDK;

use DineroSDK\Entity\Contact;
use DineroSDK\Entity\Invoice;
use DineroSDK\Exception\DineroAuthenticationFailedException;
use DineroSDK\Exception\DineroException;
use DineroSDK\Exception\DineroMissingConfigException;
use DineroSDK\Exception\DineroMissingParameterException;
use DineroSDK\Http\DineroResponse;
use DineroSDK\HttpClient\HttpClientInterface;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\ObjectProperty;

class Dinero
{
    const DINERO_API_VERSION = 'v1';
    const DINERO_BASE_URL = 'https://api.dinero.dk/' . self::DINERO_API_VERSION;
    const DINERO_OAUTH_URL = 'https://authz.dinero.dk/dineroapi/oauth/token';

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var integer
     */
    private $organizationId;

    /**
     * @var string
     */
    private $accessToken = null;

    /**
     * @var \DateTime
     */
    private $accessTokenExpireTime = null;

    private $emailSettings = [];

    /**
     * Dinero constructor.
     * @param array $config
     */
    public function __construct(array $config, HttpClientInterface $client = null)
    {
        $this->validateConfig($config);
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->apiKey = $config['api_key'];
        $this->organizationId = $config['organization_id'];

        if (isset($config['email_settings'])) {
            $this->emailSettings = (array) $config['email_settings'];
        }

        if (!$client) {
            $guzzleClient = new \GuzzleHttp\Client();
            $this->client = new \DineroSDK\HttpClient\GuzzleClient($guzzleClient);
        }
    }

    /**
     * @param array $config
     * @throws DineroMissingConfigException
     */
    private function validateConfig(array $config)
    {
        if (!isset($config['client_id']) || empty($config['client_id'])) {
            throw new DineroMissingConfigException('Please specify \'client_id\' in your config file');
        }

        if (!isset($config['client_secret']) || empty($config['client_secret'])) {
            throw new DineroMissingConfigException('Please specify \'client_secret\' in your config file');
        }

        if (!isset($config['api_key']) || empty($config['api_key'])) {
            throw new DineroMissingConfigException('Please specify \'api_key\' in your config file');
        }

        if (!isset($config['organization_id']) || empty($config['organization_id'])) {
            throw new DineroMissingConfigException('Please specify \'organization_id\' in your config file');
        }
    }

    /**
     * @param array $data
     * @return Contact
     */
    public function createContact(Contact $contact)
    {
        $contact = clone $contact;
        $endpoint = sprintf('/%s/contacts', $this->organizationId);

        if (!$contact->getName()) {
            throw new DineroMissingParameterException('Create Contact requires a \'Name\'');
        }

        if (!$contact->getIsPerson()) {
            throw new DineroMissingParameterException('Create Contact requires a \'IsPerson\'');
        }

        if (!$contact->getCountryKey()) {
            throw new DineroMissingParameterException('Create Contact requires a \'CountryKey\'');
        }

        $hydrator = new ObjectProperty();
        $data = $hydrator->extract($contact);

        $result = $this->send($endpoint, 'post', json_encode($data));

        return $contact->withContactGuid($result->getBody()['ContactGuid']);
    }

    /**
     * @param array $filterValues
     * @return Contact[]
     */
    public function findContact(array $filterValues)
    {
        if (isset($filterValues['IsPerson'])) {
            if ($filterValues['IsPerson']) {
                $filterValues['IsPerson'] = 'true';
            } else {
                $filterValues['IsPerson'] = 'false';
            }

        }

        $endpoint = sprintf('/%s/contacts', $this->organizationId);

        $filter = [];
        foreach ($filterValues as $property => $value) {
            $filter[] = $property . " " . "eq" . " " . "'" . $value . "'";
        }
        $filter = implode(";", $filter);

        $fields = 'Name,ContactGuid,ExternalReference,IsPerson,Street,Zipcode,City,CountryKey,Phone,Email,Webpage,AttPerson,VatNumber,EanNumber,PaymentConditionType,PaymentConditionNumberOfDays';

        $endpoint = $endpoint . "?queryFilter=" . $filter . "&fields=" . $fields;
        $result = $this->send($endpoint, 'get', '');
        $dineroContacts = $result->getBody()['Collection'];
        $contacts = [];
        $hydrator = new ClassMethods();
        foreach ($dineroContacts as $dineroContact) {
            $contact = new Contact($dineroContact['ContactGuid']);
            unset($dineroContact['ContactGuid']);
            $hydrator->hydrate($dineroContact, $contact);
            $contacts[] = $contact;
        }

        return $contacts;
    }

    /**
     * If $contact is supplied, the SDK will first search for the contact in Dinero and if it doesn't exist it will create it
     * @param Invoice $invoice
     * @param Contact|null $contact
     * @param bool $book
     * @return Invoice
     */
    public function createInvoice(Invoice $invoice, Contact $contact = null, $book = false)
    {
        $endpoint = sprintf('/%s/invoices', $this->organizationId);

        if (!$invoice->getContactGuid() && !$contact) {
            throw new DineroMissingParameterException('Invoice requires a \'Contact\'. You need to specify either a Contact Guid or pass a Contact Entity');
        }

        if ($contact) {
            if ($contact->getContactGuid()) {
                $invoice->setContactGuid($contact->getContactGuid());
            } else {
                $queryFilter = [];

                if ($contact->getExternalReference()) {
                    $queryFilter['ExternalReference'] = $contact->getExternalReference();
                }
                if ($contact->getName()) {
                    $queryFilter['Name'] = $contact->getName();
                }
                if ($contact->getVatNumber()) {
                    $queryFilter['VatNumber'] = $contact->getVatNumber();
                }
                if ($contact->getEanNumber()) {
                    $queryFilter['EanNumber'] = $contact->getEanNumber();
                }
                if ($contact->getIsPerson()) {
                    $queryFilter['IsPerson'] = $contact->getIsPerson();
                }
                if ($contact->getEmail()) {
                    $queryFilter['Email'] = $contact->getEmail();
                }

                $dineroLookup = $this->findContact($queryFilter);

                if (!count($dineroLookup)) {
                    $contact = $this->createContact($contact);
                } else {
                    if (count($dineroLookup) > 1) {
                        throw new DineroException('Found multiple Contacts while trying to create invoice');
                    }

                    $contact = $dineroLookup[0];
                }

                $invoice->setContactGuid($contact->getContactGuid());
            }
        }

        $hydrator = new ObjectProperty();
        $invoiceArray = $hydrator->extract($invoice);

        $invoiceLines = [];
        foreach ($invoice->getProductLines() as $productLine) {
            if (!$productLine->getUnit()) {
                throw new DineroMissingParameterException('InvoiceLine requires an \'Unit\'');
            }
            if (!$productLine->getQuantity()) {
                throw new DineroMissingParameterException('InvoiceLine requires a \'Quantity\'');
            }
            if (!$productLine->getDescription()) {
                throw new DineroMissingParameterException('InvoiceLine requires a \'Description\'');
            }
            if (!$productLine->getBaseAmountValue()) {
                throw new DineroMissingParameterException('InvoiceLine requires a \'BaseAmountValue\'');
            }
            if (!$productLine->getAccountNumber()) {
                throw new DineroMissingParameterException('InvoiceLine requires an \'AccountNumber\'');
            }
            $invoiceLines[] = $hydrator->extract($productLine);
        }

        $invoiceArray['ProductLines'] = $invoiceLines;

        $result = $this->send($endpoint, 'post', json_encode($invoiceArray))->getBody();

        if ($book) {
            $bookEndpoint = $endpoint . "/" . $result['Guid'] . "/book";
            $this->send($bookEndpoint, 'post', json_encode(['Timestamp' => $result['TimeStamp']]));
        }

        return $invoice->withGuid($result['Guid']);
    }

    /**
     * @param string $invoiceGuid
     * @param array $settings See config file for structure
     */
    public function sendInvoiceEmail(string $invoiceGuid, array $settings = [])
    {
        $endpoint = sprintf('/%s/invoices/%s/email', $this->organizationId, $invoiceGuid);
        $settings = array_replace($this->emailSettings, $settings);

        $result = $this->send($endpoint, 'post', json_encode($settings));
    }

    /**
     * @param string $url
     * @param string $method
     * @param string $body
     * @param array $headers
     * @param int $timeOut
     * @return DineroResponse
     * @throws DineroAuthenticationFailedException
     * @throws DineroException
     */
    private function send(string $url, string $method, string $body, array $headers = [], int $timeOut = 10) : DineroResponse
    {
        $headers = array_merge([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ], $headers);
        return $this->client->send(self::DINERO_BASE_URL . $url, $method, $body, $headers, $timeOut);
    }

    /**
     * @return string
     * @throws DineroAuthenticationFailedException
     * @throws \DineroSDK\Exception\DineroException
     */
    public function getAccessToken()
    {
        $now = new \DateTime();

        if (!$this->accessToken || $this->accessTokenExpireTime < $now) {
            $credentials = base64_encode($this->clientId . ':' . $this->clientSecret);
            $headers = [
                'Authorization' => 'Basic ' . $credentials,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ];
            $body = 'grant_type=password&scope=read write&username=' . $this->apiKey . '&password=' . $this->apiKey;
            /** @var DineroResponse $response */
            $response = $this->client->send(self::DINERO_OAUTH_URL, 'post', $body, $headers);
            $data = $response->getBody();

            if (!isset($data['access_token'])) {
                throw new DineroAuthenticationFailedException(sprintf('Failed obtaining access_token: %s', json_encode($data)), 401);
            }

            $seconds = (int)$data['expires_in'];
            $expire = $now->add(new \DateInterval(sprintf('PT%sS', $seconds)));
            $this->accessTokenExpireTime = $expire;
            $this->accessToken = $data['access_token'];
        }

        return $this->accessToken;
    }
}
