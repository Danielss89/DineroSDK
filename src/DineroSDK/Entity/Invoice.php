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

class Invoice
{
    /**
     * Guid from Dinero
     * @var string
     */
    protected $Guid;

    /**
     * Number of days until payment deadline.
     * If PaymentConditionNumberOfDays AND PaymentConditionType is left empty
     * they will default to the contacts default payment conditions.
     * @var integer
     */
    public $PaymentConditionNumberOfDays;

    /**
     * Type of payment condition. Valid types are: Netto, NettoCash, CurrentMonthOut, or Paid.
     * Note that if you use NettoCash or Paid, PaymentConditionNumberOfDays should be null.
     * @var string
     */
    public $PaymentConditionType;

    /**
     * @var string
     */
    public $ContactGuid;

    /**
     * The currency used on the voucher. Defaults to DKK if null.
     * Dinero will assign days date currency rate for the given currency.
     * Currencies are given i format: DKK, EUR, USD ect...
     * @var string
     */
    public $Currency;

    /**
     * The language to be used in the voucher. Available languages are 'da-DK' and 'en-GB'. Defaults to 'da-DK'.
     * @var string
     */
    public $Language;

    /**
     * Your external id This can be used for ID'ing in external apps/services e.g. a web shop.
     * The maximum length is 128 characters
     * @var string
     */
    public $ExternalReference;

    /**
     * User supplied description of the voucher e.g. 'Custom Invoice description'.
     * Defaults to document type e.g. 'Invoice', 'Offer', 'Creditnote' using the selected language.
     * @var string
     */
    public $Description;

    /**
     * @var string
     */
    public $Comment;

    /**
     * This will be automaticly transformed to the format Dinero needs(YYYY-MM-DD)
     * @var \DateTime
     */
    public $Date;

    /**
     * @var InvoiceLine[]
     */
    public $ProductLines;

    /**
     * @var string
     */
    public $Address;

    /**
     * @param string $contactGuid
     * @return Contact
     */
    public function withGuid(string $guid) : Invoice
    {
        $invoice = clone $this;
        $invoice->Guid = $guid;

        return $invoice;
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->Guid;
    }

    /**
     * @param string $Guid
     */
    public function setGuid($guid)
    {
        throw new DineroException('You can\' set Guid after entity creation. Use \'$invoice->withGuid($guid)\' to get a clone with the GUID set.');
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
     * @return string
     */
    public function getContactGuid()
    {
        return $this->ContactGuid;
    }

    /**
     * @param string $ContactGuid
     */
    public function setContactGuid($ContactGuid)
    {
        $this->ContactGuid = $ContactGuid;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->Currency;
    }

    /**
     * @param string $Currency
     */
    public function setCurrency($Currency)
    {
        $this->Currency = $Currency;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->Language;
    }

    /**
     * @param string $Language
     */
    public function setLanguage($Language)
    {
        $this->Language = $Language;
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
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param string $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->Comment;
    }

    /**
     * @param string $Comment
     */
    public function setComment($Comment)
    {
        $this->Comment = $Comment;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->Date;
    }

    /**
     * @param \DateTime $Date
     */
    public function setDate($Date)
    {
        $this->Date = $Date;
    }

    /**
     * @return InvoiceLine[]
     */
    public function getProductLines()
    {
        return $this->ProductLines;
    }

    /**
     * @param InvoiceLine[] $ProductLines
     */
    public function setProductLines($ProductLines)
    {
        $this->ProductLines = $ProductLines;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->Address;
    }

    /**
     * @param string $Address
     */
    public function setAddress($Address)
    {
        $this->Address = $Address;
    }
}
