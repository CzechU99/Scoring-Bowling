<?php 

  class Game{

    private $round = 0;
    private $roll = 0;
    private $currentThrow = 0;
    private $gameScore = 0;
    private $pinsHit = [];
    private $roundsScores = [];

    public function roll(int $pins){
      $this->pinsHit[$this->roll++] = $pins;
    }

    public function setScore(){

      $score = 0;
      $roundIndex = $this->round * 2;

      if($this->round == 0){

        $this->setZero();
        $this->roundsScores[$this->round] += $this->pinsHit[$this->currentThrow];
      
      }else{

        if($this->isDoubleStrike()){

          if($this->currentThrow == 0){
            $this->roundsScores[$this->round - 2] += $this->pinsHit[$roundIndex];
            $this->roundsScores[$this->round - 1] += $this->pinsHit[$roundIndex];
          }

        }elseif($this->isStrike()){

          if($this->currentThrow == 0){
            $this->roundsScores[$this->round - 1] += $this->pinsHit[$roundIndex];
          }elseif($this->currentThrow == 1){
            $this->roundsScores[$this->round - 1] += $this->pinsHit[$roundIndex + 1];
          }

        }elseif($this->isSpare()){

          if($this->currentThrow == 0){
            $this->roundsScores[$this->round - 1] += $this->pinsHit[$roundIndex];
          }

        }

        $this->setZero();
        if($this->round != 10){
          $this->roundsScores[$this->round] += $this->pinsHit[$this->roll - 1];
        }

        if($this->round == 9){
          if($this->isDoubleStrike()){
            $this->roundsScores[$this->round] += 10;
          }
        }

      }

      for($i = 0; $i <= $this->round; $i++){
        $score += $this->roundsScores[$i];
      }

      $this->gameScore = $score;

    }

    public function getFramesScores(){
      $sum = 0;
      for($i = 0; $i < $this->round; $i++){
        $sum += $this->roundsScores[$i];
        echo $i + 1 . ". " . $sum . "\n";
      }
    }

    public function nextRound(){
      $this->round++;
    }

    public function setZeroCurrentThrow(){
      $this->currentThrow = 0;
    }

    public function getScore(){
      return $this->gameScore;
    }

    public function incrementCurrentThrow(){
      $this->currentThrow++;
    }

    public function isBonusRound(){
      if(($this->pinsHit[18] + $this->pinsHit[19]) == 10){
        return true;
      }
    }

    private function setZero(){
      if (!isset($this->roundsScores[$this->round])) {
        $this->roundsScores[$this->round] = 0;
      } 
    }

    private function isDoubleStrike(){
      if($this->round > 1){
        $previousRound = $this->round - 1; 
        $index = $previousRound * 2;
        $doubleIndex = $index - 2;
        if($this->pinsHit[$index] == 10 && $this->pinsHit[$doubleIndex] == 10){
          return true;
        }
        return false;
      }
    }

    private function isStrike(){
      $previousRound = $this->round - 1; 
      $index = $previousRound * 2;
      if($this->pinsHit[$index] == 10){
        return true;
      }
      return false;
    }

    private function isSpare(){
      $previousRound = $this->round - 1; 
      $index = $previousRound * 2;
      if(($this->pinsHit[$index] + $this->pinsHit[$index + 1]) == 10){
        return true;
      }
      return false;
    }

  }

  $game = new Game();

  echo "--------------------------- \n";
  echo "GRA SIE ROZPOCZELA! \n";
  echo "--------------------------- \n";

  for($i = 0; $i < 10; $i++){

    $game->setZeroCurrentThrow();

    echo "--------------------------- \n";
    echo "KOLEJKA -" . $i+1 . "-\n";
    echo "--------------------------- \n";
    echo "Rzut 1: \n";

    $pins1 = (int)readline();
    $game->roll($pins1);
    $game->setScore();

    echo "Wynik: " . $game->getScore() . "\n \n";

    $game->incrementCurrentThrow();

    if($pins1 < 10){
      echo "Rzut 2: \n";
      $pins2 = (int)readline();
      $game->roll($pins2);
      $game->setScore();
      echo "Wynik: " . $game->getScore() . "\n";
    }else{
      $game->roll(0);
    }

    echo "--------------------------- \n";
    
    $game->nextRound();

    echo "\n";

  }

  $game->setZeroCurrentThrow();
  
  if($game->isBonusRound()){
    echo "--------------------------- \n";
    echo "BONUSOWY RZUT: \n";
    echo "--------------------------- \n";
    $pins1 = (int)readline();
    $game->roll($pins1);
    $game->setScore();
    echo "Wynik: " . $game->getScore() . "\n";
  }

  echo "\n";
  echo "--------------------------- \n";
  echo "KONIEC GRY! \n";
  echo "PUNKTY ZDOBYTE W POSZCZEGÓLNYCH RUNDACH: \n";
  $game->getFramesScores();
  echo "--------------------------- \n";

?>