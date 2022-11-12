<?php
namespace RIS\hl7\v_2_5;

use RIS\hl7\Segment;
/**
 * Timing/Quantity (TQ1)
 * @author hariansy4h@gmail.com
 */

class TQ1 extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "ORC", "ST", 3, "", Field::OPT_R)
			, new Field(1, "SetID", "Set ID – TQ1", "-", "", "SI", 4, "01627")
			, new Field(2, "Quantity", "Quantity", "-", "", "CQ", 20, "01628")
			, new Field(3, "RepeatPattern", "Repeat Pattern", "-", "", "RPT", 540, "01629", Field::OPT_O, "0..1", "0335")
			, new Field(4, "ExplicitTime", "Explicit Time", "-", "", "TM", 20, "01630")
			, new Field(5, "RelativeTimeAndUnits", "Relative Time and Units", "-", "", "CQ", 20, "01631")
			, new Field(6, "ServiceDuration", "Service Duration", "-", "", "CQ", 20, "01632")
			, new Field(7, "StartDate", "Start Date/Time", "-", "", "TS", 26, "01633", Field::OPT_R)
			, new Field(8, "EndDate", "End Date/Time", "-", "", "TS", 26, "01634")
			, new Field(9, "Priority", "Priority", "-", "", "CWE", 250, "01635", Field::OPT_O, "0..1", "0485")
			, new Field(10, "ConditionText", "Condition Text", "-", "", "TX", 250, "01636")
			, new Field(11, "TextInstruction", "Text Instruction", "-", "", "TX", 250, "01637", Field::OPT_O, "0..1", "0065")
			, new Field(12, "Conjunction", "Conjunction", "-", "", "ID", 10, "01638", Field::OPT_C, "0..1", "0472")
			, new Field(13, "OccurrenceDuration", "Occurrence Duration", "-", "", "CQ", 20, "01639")
			, new Field(14, "TotalOccurrences", "Total Occurrences", "-", "", "NM", 10, "01640")
		];
	}
}