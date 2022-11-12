<?php
namespace RIS\hl7\v_2_3_1;

use RIS\hl7\AbstractField;

/*
 * Field Class
 * @author hariansy4h@gmail.com
 */
 
class Field extends AbstractField {	
	protected $fields = [
		// Position (sequence) of the field within the segment.
		"SEQ" => -1,
		// field name
		"NAME" => "",
		// Name of the field in a Segment table. / Component Name: Name of a subfield in a Data Type table.
		"ELEMENT_NAME" => "",
		// Long description of this field
		"DESCRIPTION" => "",
		// Value of this field
		"VALUE" => "",
		// Field Data Type
		"DT" => "ST",
		/* Maximum length of the field.
		 * Since version 2.5, the HL7 standard also defines the maximum length of each component with a field. 
		 * Profiled HL7 messages shall conform to the HL7 standard if not otherwise stated in this document. 
		 */
		"LEN" => 0,
		// HL7 unique reference for this field
		"ITEM" => "",
		/*
		 * Usage / OPT
		 */
		"OPT" => Field::OPT_O,
		/* Minimum and maximum number of occurrences for the field in the context of this Transaction.
		 * This column is not used in profiled HL7 v2.3.1 message.
		 */
		// Table reference (for fields using a set of defined values)
		"TBL" => ""
	];

	public function __construct($seq, $name, $elementName, $description = "-", $value = "", $dt = "ST", $len = 0, $item = "", $opt = Field::OPT_O, $tbl = "") {
		$this->fields["SEQ"] = $seq;
		$this->fields["NAME"] = $name;
		$this->fields["ELEMENT_NAME"] = $elementName;
		$this->fields["DESCRIPTION"] = $description;
		$this->fields["VALUE"] = $value;
		$this->fields["DT"] = $dt;
		$this->fields["LEN"] = $len;
		$this->fields["ITEM"] = $item;
		$this->fields["OPT"] = $opt;
		$this->fields["TBL"] = $tbl;
	}
}