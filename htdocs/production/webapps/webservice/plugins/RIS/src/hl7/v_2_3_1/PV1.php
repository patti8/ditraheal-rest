<?php
namespace RIS\hl7\v_2_3_1;

use RIS\hl7\Segment;
/**
 * Patient Visit (PV1)
 * @author hariansy4h@gmail.com
 */
 
class PV1 extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "PV1", "ST", 3, "", Field::OPT_R)
			, new Field(1, "SetID", "Set ID - Patient ID", "-", "", "SI", 4, "00131")
			, new Field(2, "PatientClass", "Patient Class", "-", "", "IS", 1, "00132", Field::OPT_R, "0004")
			, new Field(3, "AssignedPatientLocation", "Assigned Patient Location", "-", "", "CX", 20, "00106", Field::OPT_R)
			, new Field(4, "AdmissionType", "Admission Type", "-", "", "IS", 2, "00134", Field::OPT_O, "0007")
			, new Field(5, "PreadmitNumber", "Preadmit Number", "-", "", "CX", 20, "00135")
			, new Field(6, "PriorPatientLocation", "Prior Patient Location", "-", "", "PL", 80, "00136")
			, new Field(7, "AttendingDoctor", "Attending Doctor", "-", "", "XCN", 60, "00137", Field::OPT_C, "0010")
			, new Field(8, "ReferringDoctor", "Referring Doctor", "-", "", "XCN", 60, "00138", Field::OPT_C, "0010")
			, new Field(9, "ConsultingDoctor", "Consulting Doctor", "-", "", "XCN", 60, "00139", Field::OPT_RE, "0010")
			, new Field(10, "HospitalService", "Hospital Service", "-", "", "IS", 3, "00140", Field::OPT_C, "0069")
			, new Field(11, "TemporaryLocation", "Temporary Location", "-", "", "PL", 80, "00141")
			, new Field(12, "PreadmitTestIndicator", "Preadmit Test Indicator", "-", "", "IS", 2, "00142", Field::OPT_O, "0087")
			, new Field(13, "ReadmissionIndicator", "Readmission Indicator", "-", "", "IS", 2, "00143", Field::OPT_O, "0092")
			, new Field(14, "AdmitSource", "Admit Source", "-", "", "IS", 3, "00144", Field::OPT_O, "0023")
			, new Field(15, "AmbulatoryStatus", "Ambulatory Status", "-", "", "IS", 2, "00145", Field::OPT_C, "0009")
			, new Field(16, "VIPIndicator", "VIP Indicator", "-", "", "IS", 2, "00146", Field::OPT_O, "0099")
			, new Field(17, "AdmittingDoctor", "Admitting Doctor", "-", "", "XCN", 60, "00147", Field::OPT_C, "0010")
			, new Field(18, "PatientType", "Patient Type", "-", "", "IS", 2, "00148", Field::OPT_O, "0018")
			, new Field(19, "VisitNumber", "Visit Number", "-", "", "CX", 20, "00149", Field::OPT_C)
			, new Field(20, "FinancialClass", "Financial Class", "-", "", "FC", 50, "00150", Field::OPT_O, "0064")
			, new Field(21, "ChargePriceIndicator", "Charge Price Indicator", "-", "", "IS", 2, "00151", Field::OPT_O, "0032")
			, new Field(22, "CourtesyCode", "Courtesy Code", "-", "", "IS", 2, "00152", Field::OPT_O, "0045")
			, new Field(23, "CreditRating", "Credit Rating", "-", "", "IS", 2, "00153", Field::OPT_O, "0046")
			, new Field(24, "ContractCode", "Contract Code", "-", "", "IS", 2, "00154", Field::OPT_O, "0044")
			, new Field(25, "ContractEffectiveDate", "Contract Effective Date", "-", "", "DT", 8, "00155")
			, new Field(26, "ContractAmount", "Contract Amount", "-", "", "NM", 12, "00156")
			, new Field(27, "ContractPeriod", "Contract Period", "-", "", "NM", 3, "00157")
			, new Field(28, "InterestCode", "Interest Code", "-", "", "IS", 2, "00158", Field::OPT_O, "0073")
			, new Field(29, "TransferToBadDebtCode", "Transfer to Bad Debt Code", "-", "", "IS", 1, "00159", Field::OPT_O, "0110")
			, new Field(30, "TransferToBadDebtDate", "Transfer to Bad Debt Date", "-", "", "DT", 8, "00160")
			, new Field(31, "BadDebtAgencyCode", "Bad Debt Agency Code", "-", "", "IS", 10, "00161", Field::OPT_O, "0021")
			, new Field(32, "BadDebtTransferAmount", "Bad Debt Transfer Amount", "-", "", "NM", 12, "00162")
			, new Field(33, "BadDebtRecoveryAmount", "Bad Debt Recovery Amount", "-", "", "NM", 12, "00163")
			, new Field(34, "DeleteAccountIndicator", "Delete Account Indicator", "-", "", "IS", 1, "00164", Field::OPT_O, "0111")
			, new Field(35, "DeleteAccountDate", "Delete Account Date", "-", "", "DT", 8, "00165")
			, new Field(36, "DischargeDisposition", "Discharge Disposition", "-", "", "IS", 3, "00166", Field::OPT_O, "0112")
			, new Field(37, "DischargedToLocation", "Discharged to Location", "-", "", "CM", 25, "00167", Field::OPT_O, "0113")
			, new Field(38, "DietType", "Diet Type", "-", "", "CE", 80, "00168", Field::OPT_O, "0114")
			, new Field(39, "ServicingFacility", "Servicing Facility", "-", "", "IS", 2, "00169", Field::OPT_O, "0115")
			, new Field(40, "BedStatus", "Bed Status", "-", "", "IS", 1, "00170", Field::OPT_O, "0116")
			, new Field(41, "AccountStatus", "Account Status", "-", "", "IS", 2, "00171", Field::OPT_O, "0117")
			, new Field(42, "PendingLocation", "Pending Location", "-", "", "PL", 80, "00172")
			, new Field(43, "PriorTemporaryLocation", "Prior Temporary Location", "-", "", "PL", 80, "00173")
			, new Field(44, "AdmitDate", "Admit Date/Time", "-", "", "TS", 26, "00174")
			, new Field(45, "DischargeDate", "Discharge Date/Time", "-", "", "TS", 26, "00175")
			, new Field(46, "CurrentPatientBalance", "Current Patient Balance", "-", "", "NM", 12, "00176")
			, new Field(47, "TotalCharges", "Total Charges", "-", "", "NM", 12, "00177")
			, new Field(48, "TotalAdjustments", "Total Adjustments", "-", "", "NM", 12, "00178")
			, new Field(49, "TotalPayments", "Total Payments", "-", "", "NM", 12, "00179")
			, new Field(50, "AlternateVisitID", "Alternate Visit ID", "-", "", "CX", 20, "00180", Field::OPT_O, "0203")
			, new Field(51, "VisitIndicator", "Visit Indicator", "-", "", "IS", 1, "01226", Field::OPT_C, "0326")
			, new Field(52, "OtherHealthcareProvider", "Other Healthcare Provider", "-", "", "XCN", 60, "01224", Field::OPT_O, "0010")
		];
	}
}