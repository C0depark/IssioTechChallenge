<?php
/**
 * @package Chess Coding Challenge
 *
 * @description - ISSIO Solution – Chess board/piece movements Coding Challenge.
 *
 * Chess is a very popular game, and almost everybody knows at least the basic moves of
 * pieces on the board. You don’t have to be a grandmaster to be able to code in this challenge.
 *
 * All we are doing is just implementing the moves of two pieces: Bishop and Rook.
 *
 * Bishops can move diagonally on the board in any direction. Rooks can move only vertically
 * or only horizontally.
 *
 * You can review the rules and the moves here: https://en.wikipedia.org/wiki/Chess#Movement
 *
 * You will be provided with the sample code that creates the table and sets up some pieces
 * (black and white) as shown on the diagram:
 *
 * Your goal is to implement the move of the Bishop (E3) and the Rook (E6). For example:
 * > $board->makeMove(['c', 3], ['e', 1])
 *
 * You take a piece in the square C3 and try to move it to E1. If the chess piece from C3 square
 * can make a successful move to E1, the method will return true. In the move is illegal, it should
 * indicate so. You will have to take into consideration all legal and illegal moves.
 *
 * Feel free to adjust all of the classes and/or adding additional methods.
 *
 * You need to document your code and cover the edge cases, which are listed at the end of the
 * “ChessChallenge.php” file. You should not spend more than 1-2 hours on the exercise.
 *
 * Your performance will be measured on your ability to work with the existing code, follow the
 * instructions, understanding the principles and concepts of Object-Oriented Programming.
 *
 * @version 1.0
 * @author Issio Solutions
 * @copyright Copyright (c) 2020, Issio Solutions, Inc
 */


/**
 * Class Square
 * @description - Contains all the logic and the data of the single square.
 * Also contains the chess piece
 */
class Square {
    private $x; // 1 - 8
    private $y; // 1 - 8
    private $color; // light-dark
    private $piece = NULL;

    /**
     * Square constructor.
     * @param string $x - horizontal coordinates [a - h]
     * @param int $y - vertical coordinates [1 - 8]
     * @param string $color - light/dark
     */
    public function __construct($x, $y, $color){
        $this->x = $x;
        $this->y = $y;
        $this->color = $color;
    }

    /**
     * @param Piece $piece - chess piece on the square
     */
    public function setPiece($piece) {
        $this->piece = $piece;
    }

    /**
     * @return Piece|null
     */
    public function getPiece() {
        return $this->piece;
    }
}

/**
 * Class Board
 * @description - Main Board setup and logic including the moves
 */
class Board {
    static $letters = ['', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
    static $width = 8;
    static $height = 8;

    // $squares grid in the format square = [][], so it could be easily
    // accessed as squares['a'][3];
    private $squares = [];

    /**
     * Board constructor.
     */
    public function __construct(){
        $switch = true;

        // Setting vertical
        for($i = 1; $i <= self::$width; $i++) {
            $l = self::$letters[$i];
            $this->squares[$l] = [];

            // Setting horizontal
            for($j = 1; $j <= self::$height; $j++) {
                $color = $switch ? 'dark' : 'light';

                $this->squares[$l][$j] = new Square($l, $j, $color);
                $switch = !$switch;
            }
        }
    }

    /**
     * getSquare - returns the selected square
     * @param string $x - horizontal coordinates [a - h]
     * @param int $y - vertical coordinates [1 - 8]
     * @return Square|null - returns a square or null if not in range
     */
    public function getSquare($x, $y) {
        if (array_key_exists($x, $this->squares)) {
            if (array_key_exists($y, $this->squares[$x])) {
                return $this->squares[$x][$y];
            }
        }
        return null;
    }



    /**
     * setPiece - setting a chess piece on the board
     * @param Piece $piece - chess piece on the board
     * @param string $x - horizontal coordinates [a - h]
     * @param int $y - vertical coordinates [1 - 8]
     */
    public function setPiece($piece, $x, $y) {
        $square = $this->getSquare($x, $y);

        $square->setPiece($piece);
        $this->setSquare($square, $x, $y);
    }

    /**
     * Build valid moves for all pieces on the board
     *
     * @return void
     */
    public function buildValidMoves() {
        foreach ($this->squares as $x => $value) {
            foreach ($value as $y => $square) {
                $piece = $square->getPiece();
                if (!is_null($piece)) {
                    $piece->buildValidMoves($x, $y, $this);
                }
            }
        }
    }

    /**
     * setSquare - setting a piece on the board
     * @param Square $square - Square
     * @param string $x - horizontal coordinates [a - h]
     * @param int $y - vertical coordinates [1 - 8]
     */
    private function setSquare($square, $x, $y) {
        $this->squares[$x][$y] = $square;
    }

    /**
     * makeMove - The implementation of the move of the piece on the board
     * picking the piece from the starting square (array), and moving to the
     * ending square (array).
     *
     * @param array $start - starting square [x, y]
     * @param array $end - finishing square [x, y]
     */
    public function makeMove($start, $end) {
        $squareStart = $this->getSquare($start[0], $start[1]);
        $squareEnd = $this->getSquare($end[0], $end[1]);

        $piece = $squareStart->getPiece();

        $string = "Checking move - From: [{$start[0]},{$start[1]}]  To: [{$end[0]},{$end[1]}]";
        if (!is_null($piece)) {
            // Determine valid moves
            if ($piece->isValidMove($squareEnd)) {
                $string .= ' -- Success!' . PHP_EOL;
            } else {
                $string .= ' -- Fail!' . PHP_EOL;
            }
        } else {
            $string .= ' -- No piece at start!' . PHP_EOL;
        }

        return $string;
    }


    /**
     * Print the board to the console
     *
     * @return void
     */
    public function printBoard() {
        $string = '';
        for ($y = 0; $y <= self::$height; $y++) {
            for ($i = 0; $i <= self::$width; $i++) {
                $x = self::$letters[$i];

                if ($y == 0) {
                    if ($i == 0) {
                        $string .= '     ';
                    } else {
                        $string .= "--{$x}--";
                    }
                } else {
                    if ($i == 0) {
                        $string .= "--{$y}--";
                    } else {
                        $square = $this->getSquare($x, $y);
                        if (!is_null($square->getPiece())) {
                            $color = substr($square->getPiece()->getColor(), 0, 1);
                            $string .= "[{$square->getPiece()->getIcon()}-{$color}]";
                        } else {
                            $string .= "[   ]";
                        }
                    }
                }
            }

            $string .= PHP_EOL;
        }

        print($string);
    }
}

/**
 * Class Piece
 * @todo - Adjust the class appropriately
 */
abstract class Piece {
    private $color; // black/white]
    private $type;

    protected $icon; // Graphical representation of the piece on the board

    protected $validSquares = []; // Array of valid squares for the piece

    /**
     * Piece constructor.
     * @param string $color - piece color [black/white]
     * @param string $type - piece type, queen, king, pawn etc.
     */
    public function __construct($color, $type, $icon){
        $this->color = $color;
        $this->type = $type;
        $this->icon = $icon;
    }

    public function getIcon() {
        return $this->icon;
    }

    /**
     * Get the color of the piece
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Build valid moves for starting point x, y
     *
     * @param string $x - horizontal coordinates [a - h]
     * @param int $y - vertical coordinates [1 - 8]
     * @param Board $board - Board object
     * @return array - Array of valid squares
     */
    abstract function buildValidMoves($x, $y, $board);

    /**
     * Determine if $square is a valid move for the piece
     *
     * @param $square - Square to validate
     * @return bool - Return true if valid
     */
    public function isValidMove($square) {
        return in_array($square, $this->validSquares);
    }
}

/**
 * Class Pawn
 */
class Pawn extends Piece {
    public function __construct($color){
        parent::__construct($color, 'Pawn', 'P');
    }

    function buildValidMoves($x, $y, $board) {
        return [];
    }
}

/**
 * Class Bishop
 */
class Bishop extends Piece {
    public function __construct($color){
        parent::__construct($color, 'Bishop', 'B');
    }

    /**
     * Build array of valid moves for the piece
     *
     * @param $x - Starting $x coordinate
     * @param $y - Starting $y coordinate
     * @param $board - Board object
     * @return void
     */
    public function buildValidMoves($x, $y, $board)
    {
        $this->validSquares = [];

        // Search -x, -y
        $loopCount = 1;
        for($i = array_search($x, Board::$letters)-1; $i > 0; $i--) {
            $x2 = Board::$letters[$i];
            $y2 = $y - $loopCount;

            $square = $board->getSquare($x2, $y2);
            if (!is_null($square)) {
                $piece = $square->getPiece();
                if (!is_null($piece) && $this->getColor() == $piece->getColor()) {
                    break;
                }

                $this->validSquares[] = $square;

                if (!is_null($piece)) {
                    break;
                }
            } else {
                break;
            }

            $loopCount++;
        }

        // Search +x, -y
        $loopCount = 1;
        for($i = array_search($x, Board::$letters)+1; $i <= Board::$width; $i++) {
            $x2 = Board::$letters[$i];
            $y2 = $y - $loopCount;

            $square = $board->getSquare($x2, $y2);
            if (!is_null($square)) {
                $piece = $square->getPiece();
                if (!is_null($piece) && $this->getColor() == $piece->getColor()) {
                    break;
                }

                $this->validSquares[] = $square;

                if (!is_null($piece)) {
                    break;
                }
            } else {
                break;
            }

            $loopCount++;
        }

        // Search -x, +y
        $loopCount = 1;
        for($i = array_search($x, Board::$letters)-1; $i > 0; $i--) {
            $x2 = Board::$letters[$i];
            $y2 = $y + $loopCount;

            $square = $board->getSquare($x2, $y2);
            if (!is_null($square)) {
                $piece = $square->getPiece();
                if (!is_null($piece) && $this->getColor() == $piece->getColor()) {
                    break;
                }

                $this->validSquares[] = $square;

                if (!is_null($piece)) {
                    break;
                }
            } else {
                break;
            }

            $loopCount++;
        }

        // Search +x, +y
        $loopCount = 1;
        for($i = array_search($x, Board::$letters)+1; $i <= Board::$width; $i++) {
            $x2 = Board::$letters[$i];
            $y2 = $y + $loopCount;

            $square = $board->getSquare($x2, $y2);
            if (!is_null($square)) {
                $piece = $square->getPiece();
                if (!is_null($piece) && $this->getColor() == $piece->getColor()) {
                    break;
                }

                $this->validSquares[] = $square;

                if (!is_null($piece)) {
                    break;
                }
            } else {
                break;
            }

            $loopCount++;
        }
    }
}

/**
 * Class Rook
 */
class Rook extends Piece {
    public function __construct($color){
        parent::__construct($color, 'Rook', 'R');
    }

    /**
     * Build array of valid moves for the piece
     *
     * @param $x - Starting $x coordinate
     * @param $y - Starting $y coordinate
     * @param $board - Board object
     * @return void
     */
    public function buildValidMoves($x, $y, $board)
    {
        $this->validSquares = [];

        // Search -x
        for($i = array_search($x, Board::$letters)-1; $i > 0; $i--) {
            $x2 = Board::$letters[$i];

            $square = $board->getSquare($x2, $y);
            if (!is_null($square)) {
                $piece = $square->getPiece();
                if (!is_null($piece) && $this->getColor() == $piece->getColor()) {
                    break;
                }

                $this->validSquares[] = $square;

                if (!is_null($piece)) {
                    break;
                }
            } else {
                break;
            }
        }
        // Search +x
        for($i = array_search($x, Board::$letters)+1; $i <= Board::$width; $i++) {
            $x2 = Board::$letters[$i];

            $square = $board->getSquare($x2, $y);
            if (!is_null($square)) {
                $piece = $square->getPiece();
                if (!is_null($piece) && $this->getColor() == $piece->getColor()) {
                    break;
                }

                $this->validSquares[] = $square;

                if (!is_null($piece)) {
                    break;
                }
            } else {
                break;
            }
        }
        // Search -y
        for($i = $y-1; $i > 0; $i--) {
            $square = $board->getSquare($x, $i);
            if (!is_null($square)) {
                $piece = $square->getPiece();
                if (!is_null($piece) && $this->getColor() == $piece->getColor()) {
                    break;
                }

                $this->validSquares[] = $square;

                if (!is_null($piece)) {
                    break;
                }
            } else {
                break;
            }
        }
        // Search +y
        for($i = $y+1; $i <= Board::$height; $i++) {
            $square = $board->getSquare($x, $i);
            if (!is_null($square)) {
                $piece = $square->getPiece();
                if (!is_null($piece) && $this->getColor() == $piece->getColor()) {
                    break;
                }

                $this->validSquares[] = $square;

                if (!is_null($piece)) {
                    break;
                }
            } else {
                break;
            }
        }
    }
}

// Setting Up the Board: START
$board = new Board();
// White Pawn on B4
$board->setPiece(new Pawn('white'), 'b', 4);
// White Pawn on E4
$board->setPiece(new Pawn('white'), 'e', 4);
// White Bishop on C3
$board->setPiece(new Bishop('white'), 'c', 3);
// Black Pawn on F6
$board->setPiece(new Pawn('black'), 'f', 6);
// Black Rook on E6
$board->setPiece(new Rook('black'), 'e', 6);

$board->buildValidMoves();
// Setting Up the Board: END

$board->printBoard();

// Moves for the White Bishop
// Bishops only move diagonally
echo $board->makeMove(['c', 3], ['e', 1]); // Success - Legal move
echo $board->makeMove(['c', 3], ['f', 6]); // Success - Take over the black pawn.

echo $board->makeMove(['c', 3], ['h', 5]); // Fail - Illegal move bishops in general
echo $board->makeMove(['c', 3], ['b', 4]); // Fail - Illegal move (white pawn on the way)
echo $board->makeMove(['c', 3], ['h', 8]); // Fail - Illegal move (cannot jump over other pieces)


// Moves for the Black Rook
// Rooks moving only vertically or horizontally
echo $board->makeMove(['e', 6], ['a', 6]); // Success - Legal move
echo $board->makeMove(['e', 6], ['e', 4]); // Success - Take over the white pawn.

echo $board->makeMove(['e', 6], ['c', 5]); // Fail - Illegal move rooks in general
echo $board->makeMove(['e', 6], ['f', 6]); // Fail - Illegal move (black pawn on the way)
echo $board->makeMove(['e', 6], ['e', 1]); //


