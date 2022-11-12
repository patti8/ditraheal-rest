<?php
namespace RIS\hl7\v_2_5;

use RIS\hl7\Segment;

/**
 * Event Type (EVN)
 * @author hariansy4h@gmail.com
 */
 
class EVN extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "EVN", "ST", 3, "", Field::OPT_R)
			, new Field(1, "EventTypeCode", "Event Type Code", "-", "", "ID", 3, "00099", Field::OPT_O, "0..1", "0003")
			, new Field(2, "RecordedDateTime", "Recorded Date/Time", "-", "", "TS", 26, "00100", Field::OPT_R)
			, new Field(3, "DateTimePlannedEvent", "Date/Time Planned Event", "-", "", "TS", 26, "00101")
			, new Field(4, "EventReasonCode", "Event Reason Code", "-", "", "IS", 3, "00102", Field::OPT_O, "0..1", "0062")
			, new Field(5, "OperatorID", "Operator ID", "-", "", "XCN", 60, "00103", Field::OPT_O, "0..1", "0188")
			, new Field(6, "EventOccurred", "Event Occurred", "-", "", "TS", 26, "01278", Field::OPT_RE)			
		];
	}
}