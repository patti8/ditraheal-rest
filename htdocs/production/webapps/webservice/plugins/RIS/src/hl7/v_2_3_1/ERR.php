<?php
namespace RIS\hl7\v_2_3_1;

use RIS\hl7\Segment;
/**
 * Error Segment (ERR)
 * @author hariansy4h@gmail.com
 */
 
class ERR extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Error Segment", "ERR", "ST", 3, "", Field::OPT_R)
			, new Field(1, "ErrorCodeAndLocation", "Error code and location", "-", "", "ID", 80, "00024", Field::OPT_R)			
		];
	}
}