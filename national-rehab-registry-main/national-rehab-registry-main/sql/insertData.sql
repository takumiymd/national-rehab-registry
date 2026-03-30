USE national_rehab_registry;

INSERT INTO Person (SSN, Name, Phone_number, Postal_code, Address) VALUES
(1001, 'Armaan', '604000001', 'V4L123', '101 Shimmer St'),
(1002, 'Quang',  '604000002', 'V4L124', '102 Shimmer St'),
(1003, 'Takumi', '604000003', 'V4L125', '103 Shimmer St'),
(1004, 'Kunal',  '604000004', 'V4L126', '104 Shimmer St'),
(1005, 'Nuts',   '604000005', 'V4L127', '105 Shimmer St');

INSERT INTO TreatmentPlan (TID, Dosage_level) VALUES
(4001, 1000),
(4002, 2000),
(4003, 3000),
(4004, 4000),
(4005, 5000);

INSERT INTO Staff (SID, SSN, SUPERVISOR_ID) VALUES
(2001, 1001, NULL),
(2002, 1002, NULL),
(2003, 1003, NULL),
(2004, 1004, NULL),
(2005, 1005, NULL);

INSERT INTO PatientMonitors (PID, TYPE_OF_ADDICTION, PROGRESS_OF_RECOVERY, SID, TID) VALUES
(8001, 'Cannabis use disorder',  'Requires less dosage of cannabis over time', 2001, 4001),
(8002, 'Alcohol use disorder',   'Supervision required in case of self harm',  2002, 4002),
(8003, 'Opioid use disorder',    'Caretaker must be present with sharp objects', 2003, 4003),
(8004, 'Gambling addiction',     'Therapist needed',                            2004, 4004),
(8005, 'Cigarette addiction',    'Requires behavioral therapies',               2005, 4005);

INSERT INTO SpecialistResponsible (SID, TID, Role) VALUES
(2001, 4001, 'Nurse'),
(2002, 4002, 'Doctor'),
(2003, 4003, 'Physiotherapist'),
(2004, 4004, 'Therapist'),
(2005, 4005, 'Caretaker');

INSERT INTO TreatmentPlanAssigned (PID, TID, DurationTreatment) VALUES
(8001, 4001, '2026-07-17'),
(8002, 4002, '2027-12-15'),
(8003, 4003, '2029-06-24'),
(8004, 4004, '2027-08-23'),
(8005, 4005, '2026-05-12');

INSERT INTO MedicationPrescribes (MID, TID, MEDICATION_NAME) VALUES
(2468,   4001, 'Ibuprofen'),
(1357,   4002, 'Methadone'),
(36912,  4003, 'Buprenorphine'),
(246810, 4004, 'Naltrexone'),
(12345,  4005, 'Varenicline');

INSERT INTO Supervises (CaretakerID, SupervisorID) VALUES
(8001, 2001),
(8002, 2002),
(8003, 2003),
(8004, 2004),
(8005, 2005);
