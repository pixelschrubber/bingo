<?php

/**
 * Creation of Bingo Sheets from a populated web form
 *
 * @author um2
 */
class Bingo {

    public $fieldwidth = 5;
    public $fieldheight = 5;
    public $cardamount = 30;
    public $cardspersheet;
    public $sortwords;
    public $freespace;
    public $occasion;
    public $bingovalues;
    public $occasionname;
    public $amount;
    public $amountbingovalues;
    private $errors;
    
    public function generateBingoCards($_POST) {
        $this->fieldwidth = $_POST["fieldwidth"];
        $this->fieldheight = $_POST["fieldheight"];
        $this->cardamount = $_POST["cardamount"];
        $this->cardspersheet = $_POST["cardspersheet"];
        $this->occasion = $_POST["occasion"];
        $this->bingovalues = explode(",",$_POST["bingovalues"]);
        $this->sortwords = $_POST["sortwords"];
        $this->freespace = $_POST["freespace"];
        $this->occasionname = $_POST["occasionname"];
        $this->amount = $this->fieldheight * $this->fieldwidth;
        $this->amountbingovalues = count($this->bingovalues);
        
        if($this->amountbingovalues < $this->amount) {
            $this->errors = 1;
            echo "<div class='error'>Attention! Not enough words/numbers ($this->amountbingovalues) to fill the desired amount of fields ($this->amount)!</div>";
        }


        for ($i = 1; $i <= $this->cardamount; $i++) {
            // Zufall
            shuffle(shuffle($this->bingovalues));
            //@todo: überprüfen ob zwei identische arrays existieren und rekursiv shuffeln
            
            // Jede Karte eigener Array
            $cardvalues = $this->bingovalues;
            
            // Array auf Anzahl der Felder kürzen
            array_splice($cardvalues, $this->amount);

            // Array sortieren, sofern gewünscht
            if($this->sortwords == "on") {
                sort($cardvalues);
            }

            
            // Mitte freilassen, sofern gewünscht
            if(($this->amount % 2) == 1) {
                if($this->freespace == "on" && $this->errors != 1) {
                    array_splice($cardvalues, round($this->amount/2)-1, 0, array("<span class='middle'>&#9733;</span>"));
                }
            }
            
            // Bingokarte generieren
            $bingocard = "<div class='bingo'>";
            if($this->occasionname != "") {
                $bingocard .= "<strong class='occasionname'>".$this->occasionname."</strong>";
            }
            $bingocard .= "<table>";
            $bingocard .= "<tbody>";
            $bingocard .= "<tr>";
            for ($cell = 0; $cell < $this->amount; $cell++) {
                $rowend = ($cell + 1) % $this->fieldheight;
                $bingocard .= "<td>"
                        . $cardvalues[$cell] . "</td>";
                if ($rowend == 0 && $cell < $this->amount - 1) {
                    $bingocard .= "</tr>\n<tr>";
                }
            }
            $bingocard .= "</tr>";
            $bingocard .= "</tbody>";
            $bingocard .= "</table>";
            $bingocard .= "<small> ($i)</small></div>";
            echo $bingocard;
        }
    }

}

?>
