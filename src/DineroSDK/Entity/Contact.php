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

namespace DineroSDK\Entity;

use DineroSDK\Exception\DineroException;
use Doctrine\ORM\Mapping as ORM;

class Contact
{
    /**
     * GUID from Dinero
     * @var string
     */
    protected $ContactGuid = null;

    /**
     * Your external id This can be used for ID'ing in external apps/services e.g. a web shop.
     * The maximum length is 128 characters
     * @var string
     */
    public $ExternalReference;

    /**
     * @var string
     * @required
     */
    public $Name;

    /**
     * @var string
     */
    public $Street;

    /**
     * @var string
     */
    public $ZipCode;

    /**
     * @var string
     */
    public $City;

    /**
     * Country key Two character string e.g. DK for Denmark, DE for Germany or SE for Sweden
     * @var string
     * @required
     */
    public $CountryKey;

    /**
     * @var string
     */
    public $Phone;

    /**
     * @var string
     */
    public $Email;

    /**
     * @var string
     */
    public $Webpage;

    /**
     * Name of the att. person in cases here the contact is a company. If IsPerson this should be NULL.
     * @var string
     */
    public $AttPerson = null;

    /**
     * VAT number. If IsPerson this should be NULL.
     * @var string
     */
    public $VatNumber;

    /**
     * EAN number. This number is used for digital invoicing. If IsPerson this should be NULL.
     * @var string
     */
    public $EanNumber = null;

    /**
     * Type of the payment condition for the contact. Netto, NettoCash or CurrentMonthOut.
     * If NettoCash, then PaymentConditionNumberOfDays should be null. Defaults to Netto.
     * @var string
     */
    public $PaymentConditionType;

    /**
     * Number of days for payment for the contact. E.g. that the contact has 7 days until payment has to be made.
     * This field depends on PaymentConditionType.
     * If left empty, defaults to 8 for all other payment types then NettoCash.
     * @var int
     */
    public $PaymentConditionNumberOfDays;

    /**
     * @var bool
     * @required
     */
    public $IsPerson;

    /**
     * Contact constructor.
     * @param string $contacGuid
     */
    public function __construct(string $contacGuid = null)
    {
        $this->ContactGuid = $contacGuid;
    }

    /**
     * @param string $contactGuid
     * @return Contact
     */
    public function withContactGuid(string $contactGuid) : Contact
    {
        $contact = clone $this;
        $contact->ContactGuid = $contactGuid;

        return $contact;
    }

    /**
     * @return string
     */
    public function getContactGuid()
    {
        return $this->ContactGuid;
    }

    /**
     * @param string $contactGuid
     */
    public function setContactGuid($contactGuid)
    {
        throw new DineroException('You can\' set ContactGuid after entity creation. Use \'$contact->withContactGuid($contactGuid)\' to get a clone with the GUID set.');
    }

    /**
     * @return string
     */
    public function getExternalReference()
    {
        return $this->ExternalReference;
    }

    /**
     * @param string $ExternalReference
     */
    public function setExternalReference($ExternalReference)
    {
        $this->ExternalReference = $ExternalReference;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     */
    public function setName($Name)
    {
        $this->Name = $Name;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->Street;
    }

    /**
     * @param string $Street
     */
    public function setStreet($Street)
    {
        $this->Street = $Street;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->ZipCode;
    }

    /**
     * @param string $ZipCode
     */
    public function setZipCode($ZipCode)
    {
        $this->ZipCode = $ZipCode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->City;
    }

    /**
     * @param string $City
     */
    public function setCity($City)
    {
        $this->City = $City;
    }

    /**
     * @return string
     */
    public function getCountryKey()
    {
        return $this->CountryKey;
    }

    /**
     * @param string $CountryKey
     */
    public function setCountryKey($CountryKey)
    {
        $this->CountryKey = strtoupper($CountryKey);
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->Phone;
    }

    /**
     * @param string $Phone
     */
    public function setPhone($Phone)
    {
        $this->Phone = $Phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * @param string $Email
     */
    public function setEmail($Email)
    {
        $this->Email = $Email;
    }

    /**
     * @return string
     */
    public function getWebpage()
    {
        return $this->Webpage;
    }

    /**
     * @param string $Webpage
     */
    public function setWebpage($Webpage)
    {
        $this->Webpage = $Webpage;
    }

    /**
     * @return string
     */
    public function getAttPerson()
    {
        return $this->AttPerson;
    }

    /**
     * @param string $AttPerson
     */
    public function setAttPerson($AttPerson)
    {
        $this->AttPerson = $AttPerson;
    }

    /**
     * @return string
     */
    public function getVatNumber()
    {
        return $this->VatNumber;
    }

    /**
     * @param string $VatNumber
     */
    public function setVatNumber($VatNumber)
    {
        $this->VatNumber = $VatNumber;
    }

    /**
     * @return string
     */
    public function getEanNumber()
    {
        return $this->EanNumber;
    }

    /**
     * @param string $EanNumber
     */
    public function setEanNumber($EanNumber)
    {
        $this->EanNumber = $EanNumber;
    }

    /**
     * @return string
     */
    public function getPaymentConditionType()
    {
        return $this->PaymentConditionType;
    }

    /**
     * @param string $PaymentConditionType
     */
    public function setPaymentConditionType($PaymentConditionType)
    {
        $this->PaymentConditionType = $PaymentConditionType;
    }

    /**
     * @return int
     */
    public function getPaymentConditionNumberOfDays()
    {
        return $this->PaymentConditionNumberOfDays;
    }

    /**
     * @param int $PaymentConditionNumberOfDays
     */
    public function setPaymentConditionNumberOfDays($PaymentConditionNumberOfDays)
    {
        $this->PaymentConditionNumberOfDays = $PaymentConditionNumberOfDays;
    }

    /**
     * @return boolean
     */
    public function getIsPerson()
    {
        return (bool) $this->IsPerson;
    }

    /**
     * @param boolean $IsPerson
     */
    public function setIsPerson($IsPerson)
    {
        $this->IsPerson = (bool) $IsPerson;
    }
}
