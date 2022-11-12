<?php
namespace RIS\hl7;

/**
 * Abstract Field Class
 * @author hariansy4h@gmail.com
 */
 
abstract class AbstractField {
	/* Usage
	 * 		Usage of the field (column noted as OPT in HL7 v2.3.1 message static definition.)
	 * 		The coded values used in this column are
	 *		R
	 *			Required: A compliant sending application shall populate all R elements with a non-empty value. 
	 *			A compliant receiving application may ignore the information conveyed by required elements. 
	 *			A compliant receiving application shall not raise an error due to the presence of a required element, 
	 *			but may raise an error due to the absence of a required element.
	 */
	const OPT_R = 0;
	/*		R+
	 *			Required as extension: This is a field optional in the original HL7 standard but required in the profiled messages. 
	 *			Only HL7 v2.3.1 messages use this notation to indicate the difference between OPT in the profiles and in the base HL7 standard.
	 */
	const OPT_R_PLUS = 1;
	/*		RE
	 *			Required but may be empty. (R2 in HL7 v2.3.1 messages)
	 *			The element may be missing from the message, but shall be sent by the sending application if there is relevant data. 
	 *			A conformant sending application shall be capable of providing all RE elements. 
	 *			If the conformant sending application knows a value for the element, then it shall send that value. 
	 *			If the conformant sending application does not know a value, then that element may be omitted. 
	 *			Receiving applications may ignore data contained in the element, 
	 *			but shall be able to successfully process the message if the element is omitted (no error message should be generated if the element is missing).
	 */
	const OPT_RE = 2;
	/*		O
	 *			Optional. The usage for this field within the message is not defined. 
	 *			The sending application may choose to populate the field; the receiving application may choose to ignore the field
	 */
	const OPT_O = 3;
	/*		C
	 *			Conditional. This usage has an associated condition predicate. (See HL7 v2.5.1, Chapter 2, Section 2.12.6.6, “Condition Predicate”.)
	 *			If the predicate is satisfied: A compliant sending application shall populate the element. 
	 *			A compliant receiving application may ignore data in the element. It may raise an error if the element is not present.
	 *			If the predicate is NOT satisfied: A compliant sending application shall NOT populate the element. 
	 *			A compliant receiving application shall NOT raise an error if the condition predicate is false and the element is not present, 
	 *			though it may raise an error if the element IS present.
	 *			The condition predicate is not explicitly defined when it depends on functional 
	 *			characteristics of the system implementing the transaction and it does not affect data consistency.
	 */	 
	const OPT_C = 4;
	/*		CE
	 *			Conditional but may be empty. This usage has an associated condition predicate. 
	 *			(See HL7 Version 2.5, Chapter 2, Section 2.12.6.6, “Condition Predicate”.)
	 *			If the conforming sending application knows the required values for the element, 
	 *			then the application must populate the element. 
	 *			If the conforming sending application does not know the values required for this element, 
	 *			then the element shall be omitted.
	 *			The conforming sending application must be capable of populating the element (when the predicate is true) for all CE elements. 
	 *			If the element is present, the conformant receiving application may ignore the values of that element. 
	 *			If the element is not present, 
	 *			the conformant receiving application shall not raise an error due to the presence or absence of the element.
	 *			If the predicate is NOT satisfied: The conformant sending application shall not populate the element. 
	 *			The conformant receiving application may raise an application error if the element is present.
	 */
	const OPT_CE = 5;
	/*		X
	 *			Not supported. For conformant sending applications, the element will not be sent.
	 *			Conformant receiving applications may ignore the element if it is sent, or may raise an application error.
	 */
	const OPT_X = 6;
	
	protected $fields = [];
	
	public function apply($options = []) {
		foreach($options as $key => $val) {
			if (count($this->fields) == 0)
				return $this->fields;
			if (isset($this->fields[$key])) {
				$this->fields[$key] = $val;
			}
		}
		return $this->fields;
	}

	public function get($name) {
		return isset($this->fields[$name]) ? $this->fields[$name] : null;
	}

	public function set($name, $val) {
		$this->fields[$name] = $val;
	}
}