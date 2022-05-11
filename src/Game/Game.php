<?php

namespace App\Game;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Deck;
use App\Card\Card;
use App\Game\Player;
use App\Game\Banken;

class Game
{
    public Player $player;
    public Banken $banken;
    public Card $randomCard;
    public Deck $deckObj;

    /**
     * @var array<Card> an array of current decks
     */
    public $currentDeck;


    /**
     * Constructor to create Game object
     */
    public function __construct()
    {
        $this->deckObj = new \App\Card\Deck();
        $this->currentDeck = $this->deckObj->deck;
        $this->player = new \App\Game\Player();
        $this->banken = new \App\Game\Banken();
    }

    /**
     * @param SessionInterface $session to draw a card from deck that is stored in session
     * @return Card
     */
    public function drawACard(SessionInterface $session): Card
    {
        $randomCard = "";
        if ($session->has("deckSpel")) {
            $deck = $session->get("deckSpel");
            if (count($deck) > 0) {
                shuffle($deck);
                $randomNumber = random_int(0, count($deck) - 1);

                $randomCard = $deck[$randomNumber];
                unset($deck[$randomNumber]);
                $deck2 = array_values($deck);
                $this->currentDeck = $deck2;

                $session->set("deckSpel", $deck2);
                $flag = 'false';
            } else {
                $flag = 'true';
            }
        } else {
            $deck = $this->deckObj->shuffle();
            $randomNumber = random_int(0, count($deck) - 1);
            $randomCard = $deck[$randomNumber];
            unset($deck[$randomNumber]);
            $deck2 = array_values($deck);
            $this->currentDeck = $deck2;
            var_dump(count($deck2));
            $session->set("deckSpel", $deck2);
            $flag = 'false';
        }

        return $randomCard;
    }


    /**
     * @param Card $card a card to get a numerical value of
     * @return int
     */
    public function getCardvalue(Card $card): int
    {
        if ($card->number == "J") {
            $value = 11;
        } elseif ($card->number == "Q") {
            $value = 12;
        } elseif ($card->number == "K") {
            $value = 13;
        } elseif ($card->number == "A") {
            $value = 14;
        } else {
            $value = $card->number;
        }

        return $value;
    }
}
