<?php
namespace RIS\hl7\v_2_5;

use RIS\hl7\Segment;

/**
 * Message Header (MSH)
 * The MSH (message header) segment contains control information set in the beginning of each
 * message sent. 
 * @author hariansy4h@gmail.com
 */
 
class MSH extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "MSH", "ST", 3, "", Field::OPT_R)
			, new Field(1, "FieldSeparator", "Field Separator", "| (the 'pipe' character)", "|", "ST", 1, "00001", Field::OPT_R)
			, new Field(2, "EncodingCharacters", "Encoding Characters", "Encoding Characters", "^~\&", "ST", 4, "00002", Field::OPT_R)
			, new Field(3, "SendingApplication", "Sending Application", "Identifies your application Example: RadiologyInformationSystemName", "", "HD", 227, "00003", Field::OPT_R)
			, new Field(4, "SendingFacility", "Sending Facility"
				, "Practice Fusion specific code unique to each partner Hardcode the value provided by your Practice Fusion implementation specialist in this field"
				, "", "HD", 227, "00004", Field::OPT_R)
			, new Field(5, "ReceivingApplication", "Receiving Application", "Identifies Practice Fusion as the destination Example: Practice Fusion", "", "HD", 227, "00005", Field::OPT_R)
			, new Field(6, "ReceivingFacility", "Receiving Facility"
				, "Identifies the healthcare".
				  "organization for which the message ".
				  "is intended ".
				  "Practice Fusion uses this field to ".
				  "route the result to the correct EHR ".
				  "account. NPI or a Practice ID is often ".
				  "used. ".
				  "Example: ".
				  "1234567893"
				, "", "HD", 227, "00006", Field::OPT_R)
			, new Field(7, "MessageDateAndTime", "Message Date and Time"
				, "Identifies the date and time the ".
				  "message was created ".
				  "Example: 20130205022300"
				, "", "TS", 26, "00007", Field::OPT_R)
			, new Field(8, "Security", "Security", "Used in some implementations for security features", "", "ST", 40, "00008", Field::OPT_X)
			, new Field(9, "MessageType", "Message Type", "Identifies message type <Message Code (ID)> ^ <Trigger Event (ID)> ^ <Message Structure (ID)> ORU^R01^ORU_R01 / ORM^O01", "", "MSG", 15, "00009", Field::OPT_R)
			, new Field(10, "MessageControlID", "Message Control ID"
				, "Contains the value the system uses ".
				  "to associate the message with the ".
				  "response to the message ".
				  "Can be any alphanumeric string ".
				  "Example: UNX3ZMH5YAPHBL63SB3"
				, "", "ST", 20, "00010", Field::OPT_R)
			, new Field(11, "ProcessingID", "Processing ID"
				, "P for in production ".
				  "D for in debugging ".
				  "T for in training "
				, "P", "PT", 3, "00011", Field::OPT_R)
			, new Field(12, "HL7Version", "HL7 Version", "<Version ID (ID)> ^ <Internationalization Code (CE)> ^ <International Version ID (CE)>", "2.3.1", "VID", 60, "00012", Field::OPT_R, "0104")
			, new Field(13, "SequenceNumber", "Sequence Number"
				, "A non-null value in this field ".
				  "indicates that the sequence number ".
				  "protocol is in use"
				, "", "NM", 15, "00013", Field::OPT_O, "0..1")
			, new Field(14, "ContinuationPointer", "Continuation Pointer"
				, "Contains the value used by a system ".
				  "to associate a continuation message ".
				  "with the message that preceded it ".
				  "when the data of an unsolicited ".
				  "observation request must be split ".
				  "into multiple messages"
				, "", "ST", 180, "00014", Field::OPT_X, "0..0")
			, new Field(15, "AcceptAcknowledgmentType", "Accept Acknowledgment Type"
				, "AL to always require accept acknowledgement messages to be returned ".
				  "NE to never require accept acknowledgements ".
				  "SU to only require accept acknowledgements for successfully transmitted messages ".
				  "ER to only require accept acknowledgements in the event of an error"
				, "", "ID", 2, "00015", Field::OPT_O, "0..0", "0155")
			, new Field(16, "ApplicationAcknowledgmentType", "Application Acknowledgment Type"
				, "AL to always require application acknowledgements to be returned ".
				  "NE to never require application acknowledgements to be returned ".
				  "SU to require application acknowledgements to be returned only in response to successfully transmitted messages ".
				  "ER to only require application acknowledgements in the event of an error"
				, "", "ID", 2, "00016", Field::OPT_O, "0..0", "0155")
			, new Field(17, "CountryCode", "Country Code"
				, "HL7 recommends values from ISO table 3166 ".
				  "Example: US for 'United States'"
				, "", "ID", 3, "00017", Field::OPT_RE, "1..1", "0399")
			, new Field(18, "CharacterSet", "Character Set"
				, "Valid character set codes are defined in HL7 table 0211 ".
				  "Example: ASCII for the ASCII character set"
				, " ", "ID", 16, "00692", Field::OPT_C, "0..1", "0211")
			, new Field(19, "PrincipalLanguageOfMessage", "Principal Language Of Message"
				, "HL7 recommends values from ISO table 639 ".
				  "Example: en for 'English'"
				, "", "CE", 250, "00693", Field::OPT_RE)
			, new Field(20, "AlternateCharacterSetHandlingScheme", "Alternate Character Set Handling Scheme", "This field can be left blank", "", "ID", 20, "01317", Field::OPT_X, "0..0", "0356")
			, new Field(21, "MessageProfileIdentifier", "Message Profile Identifier", "-", "", "EI", 427, "01598", Field::OPT_RE, "0..*")
		];
	}
	
	public function toString() {
		/* Get Field Separator/delimiter MSH_1 */
		$this->delimiter = $this->fields[1]->get("VALUE");
		$data = [];
		foreach($this->fields as $f) {
			if($f->get("SEQ") === 1) continue; // MSH_1 / FieldSeparator
			$data[] =  $f->get("VALUE");
		}
		
		return implode($this->delimiter, $data);
	}
	
	public function parse($MSHString) {
		$this->delimiter = $this->fields[1]->get("VALUE");
		$mshs = explode($this->delimiter, $MSHString);
		if($mshs == false) throw new \Exception("Message is not MSH");
		if(count($mshs) != (count($this->fields) - 1)) throw new \Exception("MSH String not match");
		if($mshs[0] != "MSH") throw new \Exception("Message is not MSH");
				
		$idx = -1;
		foreach($this->fields as $f) {
			$idx++;
			if($idx < 2) continue;
			$f->set("VALUE", $mshs[$idx - 1]);
		}
	}
}