<?php
namespace RIS\hl7\v_2_5;

use RIS\hl7\Segment;
/**
 * Order Detail (OBR)
 * @author hariansy4h@gmail.com
 */
 
class OBR extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "OBR", "ST", 3, "", Field::OPT_R)
			, new Field(1, "SetID", "Set ID - OBR", "-", "", "SI", 4, "00237")
			, new Field(2, "PlacerOrderNumber", "Placer Order Number", "-", "", "EI", 22, "00216", Field::OPT_R)
			, new Field(3, "FillerOrderNumber", "Filler Order Number", "-", "", "EI", 22, "00217")
			, new Field(4, "UniversalServiceID", "Universal Service ID", "-", "", "CE", 250, "00238", Field::OPT_R)
			, new Field(5, "Priority", "Priority", "-", "", "ID", 2, "00239")
			, new Field(6, "RequestedDate", "Requested Date/time", "-", "", "TS", 26, "00240")
			, new Field(7, "ObservationDate", "Observation Date/Time", "-", "", "TS", 26, "00241")
			, new Field(8, "ObservationEndDate", "Observation End Date/Time", "-", "", "TS", 26, "00242")
			, new Field(9, "CollectionVolume", "Collection Volume", "-", "", "CQ", 20, "00243")
			, new Field(10, "CollectorIdentifier", "Collector Identifier", "-", "", "XCN", 250, "00244")
			, new Field(11, "SpecimenActionCode", "Specimen Action Code", "-", "", "ID", 1, "00245", Field::OPT_O, "0..1", "0065")
			, new Field(12, "DangerCode", "Danger Code", "-", "", "CE", 250, "00246", Field::OPT_RE)
			, new Field(13, "RelevantClinicalInfo", "Relevant Clinical Info", "-", "", "ST", 300, "00247", Field::OPT_C)
			, new Field(14, "SpecimenReceivedDate", "Specimen Received Date/Time", "-", "", "TS", 26, "00248", Field::OPT_X)
			, new Field(15, "SpecimenSource", "Specimen Source", "-", "", "SPS", 300, "00249", Field::OPT_X, "1..1", "0070")
			, new Field(16, "OrderingProvider", "Ordering Provider", "-", "", "XCN", 250, "00226", Field::OPT_R)
			, new Field(17, "OrderCallbackPhoneNumber", "Order Callback Phone Number", "-", "", "XTN", 250, "00250")
			, new Field(18, "PlacerField1", "Placer field 1", "-", "", "ST", 60, "00251")
			, new Field(19, "PlacerField2", "Placer field 2", "-", "", "ST", 60, "00252")
			, new Field(20, "FillerField1", "Filler field 1", "-", "", "ST", 60, "00253")
			, new Field(21, "FillerField2", "Filler field 2", "-", "", "ST", 60, "00254")
			, new Field(22, "ResultsRptOrStatusChngDate", "Results Rpt/Status Chng - Date/Time", "-", "", "TS", 26, "00255")
			, new Field(23, "ChargeToPractice", "Charge to Practice", "-", "", "MOC", 40, "00256")
			, new Field(24, "DiagnosticServSectID", "Diagnostic Serv Sect ID", "-", "", "ID", 10, "00257", Field::OPT_O, "0..1", "0074")
			, new Field(25, "ResultStatus", "Result Status", "-", "", "ID", 1, "00258", Field::OPT_O, "0..1", "0123")
			, new Field(26, "ParentResult", "Parent Result", "-", "", "PRL", 400, "00259")
			, new Field(27, "Quantity", "Quantity/Timing", "-", "", "TQ", 200, "00221", Field::OPT_X)
			, new Field(28, "ResultCopiesTo", "Result Copies To", "-", "", "XCN", 250, "00260")
			, new Field(29, "Parent", "Parent", "-", "", "EIP", 150, "00261", Field::OPT_C)
			, new Field(30, "TransportationMode", "Transportation Mode", "-", "", "ID", 20, "00262", Field::OPT_RE, "1..1", "0124")
			, new Field(31, "ReasonForStudy", "Reason for Study", "-", "", "CE", 250, "00263", Field::OPT_RE)
			, new Field(32, "PrincipalResultInterpreter", "Principal Result Interpreter", "-", "", "NDL", 200, "00264")
			, new Field(33, "AssistantResultInterpreter", "Assistant Result Interpreter", "-", "", "NDL", 200, "00265")
			, new Field(34, "Technician", "Technician", "-", "", "NDL", 200, "00266")
			, new Field(35, "Transcriptionist", "Transcriptionist", "-", "", "NDL", 200, "00267")
			, new Field(36, "ScheduledDate", "Scheduled Date/Time", "-", "", "TS", 26, "00268")
			, new Field(37, "NumberOfSampleContainers", "Number of Sample Containers", "-", "", "NM", 4, "01028")
			, new Field(38, "TransportLogisticsOfCollectedSample", "Transport Logistics of Collected Sample", "-", "", "CE", 250, "01029")
			, new Field(39, "CollectorComment", "Collector's Comment", "-", "", "CE", 250, "01030")
			, new Field(40, "TransportArrangementResponsibility", "Transport Arrangement Responsibility", "-", "", "CE", 250, "01031")
			, new Field(41, "TransportArranged", "Transport Arranged", "-", "", "ID", 30, "01032", Field::OPT_RE, "1..1", "0224")
			, new Field(42, "EscortRequired", "Escort Required", "-", "", "ID", 1, "01033", Field::OPT_O, "0225")
			, new Field(43, "PlannedPatientTransportComment", "Planned Patient Transport Comment", "-", "", "CE", 250, "01034")
			, new Field(44, "ProcedureCode", "Procedure Code", "-", "", "CE", 250, "00393", Field::OPT_O, "0..1", "0088")
			, new Field(45, "ProcedureCodeModifier", "Procedure Code Modifier", "-", "", "CE", 250, "01036", Field::OPT_O, "0340")
			, new Field(46, "PlacerSupplementalServiceInformation", "Placer Supplemental Service Information", "-", "", "CE", 250, "01474", Field::OPT_RE, "1..1", "0411")
			, new Field(47, "FillerSupplementalServiceInformation", "Filler Supplemental Service Information", "-", "", "CE", 250, "01475", Field::OPT_RE, "1..1", "0411")
			, new Field(48, "MedicallyNecessaryDuplicateProcedureReason", "Medically Necessary Duplicate Procedure Reason", "-", "", "CWE", 250, "01646", Field::OPT_RE, "1..1", "0476")
			, new Field(49, "ResultHandling", "Result Handling", "-", "", "IS", 2, "01647", Field::OPT_O, "0..1", "0507")
			, new Field(50, "ParentUniversalServiceIdentifier", "Parent Universal Service Identifier", "-", "", "CWE", 250, "02286", Field::OPT_O)
		];
	}
}