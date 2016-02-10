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

use Money\Money;

class InvoiceLine
{
    /**
     * @var string
     */
    public $Description;

    /**
     * @var string
     */
    public $Comments;

    /**
     * @var
     * Amount without tax
     */
    public $BaseAmountValue;

    /**
     * @var string Decimal
     */
    public $Quantity;

    /**
     * @var integer
     */
    public $AccountNumber;

    /**
     * Product unit. Available unit types: hours, parts, km, day, week, month, kilogram, cubicMetre, set, litre, box,
     * case, carton, metre, package, shipment, squareMetre, session.
     * @var string
     */
    public $Unit;

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
    public function getComments()
    {
        return $this->Comments;
    }

    /**
     * @param string $Comments
     */
    public function setComments($Comments)
    {
        $this->Comments = $Comments;
    }

    /**
     * @return float
     */
    public function getBaseAmountValue()
    {
        return $this->BaseAmountValue;
    }

    /**
     * @param Money $BaseAmountValue
     */
    public function setBaseAmountValue($BaseAmountValue)
    {
        $this->BaseAmountValue = $BaseAmountValue;
    }

    /**
     * @return string
     */
    public function getQuantity()
    {
        return $this->Quantity;
    }

    /**
     * @param string $Quantity
     */
    public function setQuantity($Quantity)
    {
        $this->Quantity = $Quantity;
    }

    /**
     * @return int
     */
    public function getAccountNumber()
    {
        return $this->AccountNumber;
    }

    /**
     * @param int $AccountNumber
     */
    public function setAccountNumber($AccountNumber)
    {
        $this->AccountNumber = $AccountNumber;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->Unit;
    }

    /**
     * @param string $Unit
     */
    public function setUnit($Unit)
    {
        $this->Unit = $Unit;
    }
}
