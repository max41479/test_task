<?php

abstract class Object {

	public $base_coordinate;
	public $color;
	public $size_multiplier;

	public function __construct( $x, $y, $color, $size_multiplier ) {
		$this->set_base_coordinate( $x, 'x' );
		$this->set_base_coordinate( $y, 'y' );
		$this->set_color( $color );
		$this->set_size_multiplier( $size_multiplier );
	}

	protected function set_base_coordinate( $coordinate, $coordinate_name ) {
		if ( is_numeric( $coordinate ) ) {
			$this->base_coordinate[ $coordinate_name ] = (float) $coordinate;
		} else {
			throw new InvalidArgumentException(
				"Базовая координата $coordinate_name не является числом, 
				повторите ввод"
			);
		}
	}

	protected function set_color( $color ) {
		$pattern = '/#([a-f0-9]{3}){1,2}\b/i';
		if ( preg_match_all( $pattern, $color ) === 1 ) {
			$this->color = $color;
		} else {
			throw new InvalidArgumentException(
				"Цвет не соответствует формату HEX (#fff или #ffffff), повторите
				 ввод"
			);
		}
	}

	protected function set_size_multiplier( $size_multiplier ) {
		if ( is_numeric( $size_multiplier ) ) {
			if ( $size_multiplier > 0 ) {
				$this->size_multiplier = (float) $size_multiplier;
			} else {
				throw new InvalidArgumentException(
					"Размер должен быть больше 0, повторите ввод"
				);
			}
		} else {
			throw new InvalidArgumentException(
				"Размер не является числом, повторите ввод"
			);
		}
	}
}

trait Radius {

	private function set_radius( $radius ) {
		if ( is_numeric( $radius ) ) {
			if ( $radius > 0 ) {
				$this->radius = (float) $radius;
			} else {
				throw new InvalidArgumentException(
					"Радиус должен быть больше 0, повторите ввод"
				);
			}
		} else {
			throw new InvalidArgumentException(
				"Радиус не является числом, повторите ввод"
			);
		}
	}
}

trait EndCoordinates {

	private function set_end_coordinate( $coordinate, $coordinate_name ) {
		if ( is_numeric( $coordinate ) ) {
			$this->end_coordinate[ $coordinate_name ] = (float) $coordinate;
		} else {
			throw new InvalidArgumentException(
				"Конечная координата $coordinate_name не является числом,
				повторите ввод"
			);
		}
	}
}

class Dot extends Object {

	public function __construct( $x, $y, $color, $size_multiplier ) {
		parent::__construct( $x, $y, $color, $size_multiplier );
	}
}

class Line extends Object {

	use EndCoordinates;
	public $end_coordinate;

	public function __construct(
		$x, $y, $color, $size_multiplier, $end_x, $end_y
	) {
		parent::__construct( $x, $y, $color, $size_multiplier );
		$this->set_end_coordinate( $end_x, 'x' );
		$this->set_end_coordinate( $end_y, 'y' );
	}
}

class Arc extends Object {

	use Radius, EndCoordinates;
	public $angle;
	public $end_coordinate;

	public function __construct(
		$x, $y, $color, $size_multiplier, $end_x, $end_y, $angle
	) {
		parent::__construct( $x, $y, $color, $size_multiplier );
		$this->set_end_coordinate( $end_x, 'x' );
		$this->set_end_coordinate( $end_y, 'y' );
		$this->set_angle( $angle );
	}

	private function set_angle( $angle ) {
		if ( is_numeric( $angle ) ) {
			if ( $angle >= 0 && $angle <= 360 ) {
				$this->angle = (float) $angle;
			} else {
				throw new InvalidArgumentException(
					"Угол должен быть в пределах 360 градусов"
				);
			}

		} else {
			throw new InvalidArgumentException(
				"Угол не является числом, повторите ввод"
			);
		}
	}
}

class Circle extends Object {

	use Radius;
	public $radius;

	public function __construct( $x, $y, $color, $size_multiplier, $radius ) {
		parent::__construct( $x, $y, $color, $size_multiplier );
		$this->set_radius( $radius );
	}
}

$dot    = new Dot( 0, 0, '#fff', 1 );
$line   = new Line( 0, 0, '#fff', 1, 1, 1 );
$arc    = new Arc( 0, 0, '#fff', 1, 1, 1, 1 );
$circle = new Circle( 0, 0, '#fff', 1, 1 );