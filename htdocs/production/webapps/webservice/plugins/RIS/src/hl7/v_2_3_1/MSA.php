<?php
namespace RIS\hl7\v_2_3_1;

use RIS\hl7\Segment;

/**
 * Message Acknowledgement (MSA)
 * @author hariansy4h@gmail.com
 */
 
class MSA extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Acknowledgement Segment", "MSA", "ST", 3, "", Field::OPT_R)
			, new Field(1, "AcknowledgmentCode", "Acknowledgment Code", "-", "", "ID", 2, "00018", Field::OPT_R, "0008")
			, new Field(2, "MessageControlID", "Message Control ID"
				, "Contains the value the system uses ".
				  "to associate the message with the ".
				  "response to the message ".
				  "Can be any alphanumeric string ".
				  "Example: UNX3ZMH5YAPHBL63SB3"
				, "", "ST", 20, "00010", Field::OPT_R)
			, new Field(3, "TextMessage", "Text Message", "-", "", "ST", 80, "00020")
			, new Field(4, "ExpectedSequenceNumber", "Expected Sequence Number", "-", "", "NM", 15, "00021")
			, new Field(5, "DelayedAcknowledgmentType", "Delayed Acknowledgment Type", "-", "", "ID", 1, "00022", Field::OPT_O, "0102")
			, new Field(6, "ErrorCondition", "Error Condition", "-", "", "CE", 100, "00023")			
		];
	}
}