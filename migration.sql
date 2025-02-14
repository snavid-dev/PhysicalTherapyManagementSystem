-- users
INSERT INTO canin.users (id, fname, lname, role, username, status, password, photo, uniqid, role_id, working_start_time,
						 working_end_time)
SELECT id,
	   fname,
	   lname,
	   role,
	   username,
	   status,
	   password,
	   photo,
	   uniqid,
	   NULL,
	   '08:00',
	   '17:00'
FROM haidari.users ON DUPLICATE KEY
UPDATE
	fname =
VALUES (fname), lname =
VALUES (lname), role =
VALUES (role), status =
VALUES (status);

-- services
INSERT INTO canin.services (id, name, price, department)
SELECT id, name, price, department
FROM haidari.services ON DUPLICATE KEY
UPDATE
	name =
VALUES (name), price =
VALUES (price), department =
VALUES (department);

-- customers
INSERT
INTO canin.customers (id, name, lname, phone, type, users_id)
SELECT id, name, lname, phone, type, users_id
FROM haidari.customers ON DUPLICATE KEY
UPDATE
	name =
VALUES (name), lname =
VALUES (lname), phone =
VALUES (phone), type =
VALUES (type), users_id =
VALUES (users_id);


-- Balance sheet

INSERT
INTO canin.`balance_sheet` (`id`, `cr`, `dr`, `create`, `remarks`, `shamsi`, `customers_id`, `users_id`)
SELECT `id`,
	   `cr`,
	   `dr`,
	   `create`,
	   `remarks`,
	   `shamsi`,
	   `customers_id`,
	   `users_id`
FROM haidari.`balance_sheet`;

-- Patient

INSERT
INTO canin.`patient` (`id`, `name`, `lname`, `phone1`, `phone2`, `age`, `address`, `pains`, `gender`, `other_pains`,
					  `serial_id`, `create`, `users_id`, `status`, `remarks`, `doctor_id`)
SELECT `id`,
	   `name`,
	   `lname`,
	   `phone1`,
	   `phone2`,
	   `age`,
	   `address`,
	   `pains`,
	   `gender`,
	   `other_pains`,
	   `serial_id`,
	   `create`,
	   `users_id`,
	   `status`,
	   `remarks`,
	   `doctor_id`
FROM haidari.`patient`;


-- Turn


INSERT
INTO canin.turn (id, patient_id, date, from_time, to_time, status, cr, pay_date, doctor_id)
SELECT id,
	   patient_id, date, SUBSTRING_INDEX(hour, ',', 1) AS from_time, -- Extract first part
	SUBSTRING_INDEX(hour, ',', -1) AS to_time,                       -- Extract second part
	status, cr, pay_date, doctor_id
FROM haidari.turn;


-- teeth

INSERT
INTO canin.`tooth` (`id`, `name`, `location`, `create_date`, `imgAddress`, `price`, `users_id`, `patient_id`)
SELECT `id`,
	   `name`,
	   `location`,
	   `create_date`,
	   `imgAddress`,
	   `price`,
	   `users_id`,
	   `patient_id`
FROM haidari.`tooth`;


-- Tooth has diagnose

INSERT
INTO canin.`tooth_has_diagnose` (`tooth_id`, `diagnose_id`)
SELECT `tooth_id`,
	   `diagnose_id`
FROM haidari.`tooth_has_diagnose`;


-- endo
INSERT
INTO canin.`endo` (`id`, `r_name1`, `r_width1`, `r_name2`, `r_width2`, `r_name3`, `r_width3`, `r_name4`, `r_width4`,
				   `r_name5`,
				   `r_width5`, `services`, `price`, `details`, `root_number`, `modify_date`, `tooth_id`)
SELECT `id`,
	   `r_name1`,
	   `r_width1`,
	   `r_name2`,
	   `r_width2`,
	   `r_name3`,
	   `r_width3`,
	   `r_name4`,
	   `r_width4`,
	   `r_name5`,
	   `r_width5`,
	   `services`,
	   `price`,
	   `details`,
	   `root_number`,
	   `modify_date`,
	   `tooth_id`
FROM haidari.`endo`;


-- endo has basic information

INSERT
INTO canin.`endo_has_basic_information_teeth` (`endo_id`, `basic_information_teeth_id`)
SELECT `endo_id`,
	   `basic_information_teeth_id`
FROM haidari.`endo_has_basic_information_teeth`;


-- endo has service
INSERT
INTO canin.`endo_has_services` (`endo_id`, `services_id`)
SELECT `endo_id`,
	   `services_id`
FROM haidari.`endo_has_services`;


-- restorative
INSERT
INTO canin.`restorative` (`id`, `details`, `services`, `price`, `modify_date`, `tooth_id`)
SELECT `id`, `details`, `services`, `price`, `modify_date`, `tooth_id`
FROM haidari.`restorative`;


-- restorative has basic information

INSERT
INTO canin.`restorative_has_basic_information_teeth` (`restorative_id`, `basic_information_teeth_id`)
SELECT `restorative_id`,
	   `basic_information_teeth_id`
FROM haidari.`restorative_has_basic_information_teeth`;

-- restorative has service

INSERT
INTO canin.`restorative_has_services` (`restorative_id`, `services_id`)
SELECT `restorative_id`,
	   `services_id`
FROM haidari.`restorative_has_services`;


-- prosthodontics
INSERT
INTO canin.`prosthodontics` (`id`, `details`, `services`, `price`, `modify_date`, `tooth_id`)
SELECT `id`, `details`, `services`, `price`, `modify_date`, `tooth_id`
FROM haidari.`prosthodontics`;


-- prosthodontics has basic information

INSERT
INTO canin.`prosthodontics_has_basic_information_teeth` (`prosthodontics_id`, `basic_information_teeth_id`)
SELECT `prosthodontics_id`,
	   `basic_information_teeth_id`
FROM haidari.`prosthodontics_has_basic_information_teeth`;

-- prosthodontics has service

INSERT
INTO canin.`prosthodontics_has_services` (`prosthodontics_id`, `services_id`)
SELECT `prosthodontics_id`,
	   `services_id`
FROM haidari.`prosthodontics_has_services`;


-- prescription

INSERT
INTO canin.`prescription` (`id`, `medicine_1`, `usageType_1`, `day_1`, `time_1`, `doze_1`, `unit_1`, `amount_1`,
				   `medicine_2`, `usageType_2`, `day_2`, `time_2`, `doze_2`, `unit_2`, `amount_2`,
				   `medicine_3`, `usageType_3`, `day_3`, `time_3`, `doze_3`, `unit_3`, `amount_3`,
				   `medicine_4`, `usageType_4`, `day_4`, `time_4`, `doze_4`, `unit_4`, `amount_4`,
				   `medicine_5`, `usageType_5`, `day_5`, `time_5`, `doze_5`, `unit_5`, `amount_5`,
				   `medicine_6`, `usageType_6`, `day_6`, `time_6`, `doze_6`, `unit_6`, `amount_6`,
				   `medicine_7`, `usageType_7`, `day_7`, `time_7`, `doze_7`, `unit_7`, `amount_7`,
				   `medicine_8`, `usageType_8`, `day_8`, `time_8`, `doze_8`, `unit_8`, `amount_8`,
				   `medicine_9`, `usageType_9`, `day_9`, `time_9`, `doze_9`, `unit_9`, `amount_9`,
				   `medicine_10`, `usageType_10`, `day_10`, `time_10`, `doze_10`, `unit_10`, `amount_10`,
				   `patient_id`, `users_id`, `date_time`)
SELECT `id`,
	   `medicine_1`,
	   `usageType_1`,
	   `day_1`,
	   `time_1`,
	   `doze_1`,
	   `unit_1`,
	   `amount_1`,
	   `medicine_2`,
	   `usageType_2`,
	   `day_2`,
	   `time_2`,
	   `doze_2`,
	   `unit_2`,
	   `amount_2`,
	   `medicine_3`,
	   `usageType_3`,
	   `day_3`,
	   `time_3`,
	   `doze_3`,
	   `unit_3`,
	   `amount_3`,
	   `medicine_4`,
	   `usageType_4`,
	   `day_4`,
	   `time_4`,
	   `doze_4`,
	   `unit_4`,
	   `amount_4`,
	   `medicine_5`,
	   `usageType_5`,
	   `day_5`,
	   `time_5`,
	   `doze_5`,
	   `unit_5`,
	   `amount_5`,
	   `medicine_6`,
	   `usageType_6`,
	   `day_6`,
	   `time_6`,
	   `doze_6`,
	   `unit_6`,
	   `amount_6`,
	   `medicine_7`,
	   `usageType_7`,
	   `day_7`,
	   `time_7`,
	   `doze_7`,
	   `unit_7`,
	   `amount_7`,
	   `medicine_8`,
	   `usageType_8`,
	   `day_8`,
	   `time_8`,
	   `doze_8`,
	   `unit_8`,
	   `amount_8`,
	   `medicine_9`,
	   `usageType_9`,
	   `day_9`,
	   `time_9`,
	   `doze_9`,
	   `unit_9`,
	   `amount_9`,
	   `medicine_10`,
	   `usageType_10`,
	   `day_10`,
	   `time_10`,
	   `doze_10`,
	   `unit_10`,
	   `amount_10`,
	   `patient_id`,
	   `users_id`,
	   `date_time`
FROM haidari.`prescription`;
