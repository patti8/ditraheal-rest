<?php
namespace RIS\hl7\v_2_3_1;

use RIS\hl7\Segment;

/**
 * ZDS
 * @author hariansy4h@gmail.com
 */
 
class ZDS extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "ZDS", "ST", 3, "", Field::OPT_R)
			, new Field(1, "StudyUUID", "Study Instance UID", "-", "", "ID", 36, "", Field::OPT_R)
		];
	}
}