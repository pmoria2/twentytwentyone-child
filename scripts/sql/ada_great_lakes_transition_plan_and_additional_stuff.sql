 
use adagreatlakes03_db;
use adagreatlakes09_db;
use adagreatlakes08_db;


CREATE TABLE `wp_transition_plans_import` (
  `CITY/COUNTY` text,
  `STATE` text,
  `UNIT TYPE` text,
  `COUNTY` text,
  `CENSUS REGION` text,
  `MSA URBANITY` text,
  `TOTAL POPULATION` text,
  `QUARTPOP` int(11) DEFAULT NULL,
  `MEDIAN HH INCOME` text,
  `INCOME GROUP` text,
  `POVERTY RATE` text,
  `QUARTPOVERTY` int(11) DEFAULT NULL,
  `MEDIAN AGE` double DEFAULT NULL,
  `% OF POP. 65 & OVER` text,
  `QUART % 65 & OVER` int(11) DEFAULT NULL,
  `DISABLED POPULATION` text,
  `% OF TOTAL POP. DISABLED` text,
  `QUART%DISABLED` int(11) DEFAULT NULL,
  `USE` text,
  `AUDITED` text,
  `AUDIT SCORE- REQUIRMENTS MET` text,
  `AUDIT SCORE- BEST/GOOD PRACTICE MET` text,
  `RETREIVAL METHOD` text,
  `YEAR OF MOST RECENT PLAN` int(11) DEFAULT NULL,
  `RETREIVAL  YEAR` int(11) DEFAULT NULL,
  `URL` text,
  `ADA CONTACT` text,
  `FILE NAME` text,
  `FILE LOCATION` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* The import view were much of the data manipulation takes place to make the data and field names easier to work with */
create view transition_plans_import_v as 
 SELECT `wp_transition_plans_import`.`CITY/COUNTY` as city_county,
 `wp_transition_plans_import`.`STATE`as state_cd,
 `wp_transition_plans_import`.`UNIT TYPE` as unit_type,
 CONCAT(UCASE(LEFT(`wp_transition_plans_import`.`COUNTY`, 1)),lower(SUBSTRING(`wp_transition_plans_import`.`COUNTY`, 2))) as county,
 `wp_transition_plans_import`.`CENSUS REGION` as census_region,
 lower(`wp_transition_plans_import`.`MSA URBANITY`) as msa_urbanity,
 convert(replace(`wp_transition_plans_import`.`TOTAL POPULATION`,",",""), DECIMAL(7,0)) as total_pop,
 `wp_transition_plans_import`.`TOTAL POPULATION` as total_pop2,
 `wp_transition_plans_import`.`QUARTPOP` as quart_pop,
 convert(replace(replace(`wp_transition_plans_import`.`MEDIAN HH INCOME`,"$",""),",",""), DECIMAL(7,0)) as median_hh_income,
 `wp_transition_plans_import`.`MEDIAN HH INCOME` as median_hh_income2,
 lower(trim(`wp_transition_plans_import`.`INCOME GROUP`)) as income_group,
 .01*convert(`wp_transition_plans_import`.`POVERTY RATE`, DECIMAL(4,2)) as poverty_rate,
 `wp_transition_plans_import`.`POVERTY RATE` as poverty_rate2,
 `wp_transition_plans_import`.`QUARTPOVERTY` as quart_poverty,
 `wp_transition_plans_import`.`MEDIAN AGE` as median_age, 
 .01*convert(`wp_transition_plans_import`.`% OF POP. 65 & OVER`, DECIMAL(4,2)) as pct_pop_senior,
 `wp_transition_plans_import`.`% OF POP. 65 & OVER` as pct_pop_senior2,
 `wp_transition_plans_import`.`QUART % 65 & OVER` as quart_pct_senior,
 convert(replace(`wp_transition_plans_import`.`DISABLED POPULATION`,",",""), DECIMAL(7,0)) as disabled_pop,
 `wp_transition_plans_import`.`DISABLED POPULATION` as disabled_pop2,
 .01*convert(`wp_transition_plans_import`.`% OF TOTAL POP. DISABLED`, DECIMAL(4,2)) as pct_pop_disabled,
 `wp_transition_plans_import`.`% OF TOTAL POP. DISABLED` as pct_pop_disabled2,
 `wp_transition_plans_import`.`QUART%DISABLED` as quart_pct_disabled,
 `wp_transition_plans_import`.`USE` as _use,
 IF(`wp_transition_plans_import`.`AUDITED`='Yes', 1, 0) as audited,
 `wp_transition_plans_import`.`AUDITED` as audited2,
 .01*convert(replace(`wp_transition_plans_import`.`AUDIT SCORE- REQUIRMENTS MET`,"%",""), DECIMAL(10,4)) as audit_score_req_met,
 `wp_transition_plans_import`.`AUDIT SCORE- REQUIRMENTS MET` as audit_score_req_met2,
 .01*convert(replace(`wp_transition_plans_import`.`AUDIT SCORE- BEST/GOOD PRACTICE MET`,"%",""), DECIMAL(10,4)) as audit_score_best_prct_met,  
 `wp_transition_plans_import`.`AUDIT SCORE- BEST/GOOD PRACTICE MET` as audit_score_best_prct_met2,
 `wp_transition_plans_import`.`RETREIVAL METHOD` as retreival_method,
 `wp_transition_plans_import`.`YEAR OF MOST RECENT PLAN` as plan_year,
 `wp_transition_plans_import`.`RETREIVAL  YEAR` as retreival_year,
 `wp_transition_plans_import`.`URL` as plan_url,
 `wp_transition_plans_import`.`ADA CONTACT` as ada_contact,
 `wp_transition_plans_import`.`FILE NAME` as file_name,
 `wp_transition_plans_import`.`FILE LOCATION` as file_location,
 date(now()) as upload_date
 FROM `wp_transition_plans_import`;

/* We are going to need an account ID to pass around via AJAX in order to update the "Current Record" area */
CREATE TABLE `wp_transition_plans_districts` (
  `city_county` varchar(50) DEFAULT NULL,
  `state_cd` varchar(5) DEFAULT NULL,
  `unit_type` varchar(25) DEFAULT NULL,
  `county` varchar(50) DEFAULT NULL,
  `district_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
Insert into wp_transition_plans_districts(city_county, state_cd, unit_type, county)
select city_county, state_cd, unit_type, county from transition_plans_import_v order by state_cd, unit_type, city_county;
*/

/* Import only new district records */
Insert into wp_transition_plans_districts(city_county, state_cd, unit_type, county)
Select imp.city_county, imp.state_cd, imp.unit_type, imp.county from transition_plans_import_v imp left join wp_transition_plans_districts dist on imp.city_county=dist.city_county and imp.state_cd=dist.state_cd and imp.unit_type=dist.unit_type and imp.county=dist.county where dist.district_id is null order by state_cd, county, city_county;

/* List district records that are not included in this new imported set */
Select dist.district_id, dist.city_county, dist.state_cd, dist.unit_type, dist.county from transition_plans_import_v imp right join wp_transition_plans_districts dist on imp.city_county=dist.city_county and imp.state_cd=dist.state_cd and imp.unit_type=dist.unit_type and imp.county=dist.county where imp.census_region is null order by state_cd, county, city_county;

CREATE TABLE `wp_transition_plans` (
  `district_id` int(11) NOT NULL,
  `census_region` varchar(25) DEFAULT NULL,
  `msa_urbanity` varchar(45) DEFAULT NULL,
  `total_pop` int(10) unsigned DEFAULT '0',
  `total_pop2` varchar(25) DEFAULT NULL,
  `pop_group` varchar(20) DEFAULT 'Null',
  `pop_group_max` int(10) DEFAULT '0',
  `quart_pop` smallint(6) NOT NULL DEFAULT '0',
  `median_hh_income` int(10) unsigned DEFAULT '0',
  `median_hh_income2` varchar(25) DEFAULT NULL,
  `income_group` varchar(25) DEFAULT NULL,
  `poverty_rate` decimal(8,6) DEFAULT '0.000000',
  `poverty_rate2` varchar(25) DEFAULT NULL,
  `quart_poverty` smallint(6) NOT NULL DEFAULT '0',
  `median_age` double DEFAULT '0',
  `pct_pop_senior` decimal(8,6) DEFAULT '0.000000',
  `pct_pop_senior2` varchar(25) DEFAULT NULL,
  `quart_pct_senior` smallint(6) NOT NULL DEFAULT '0',
  `disabled_pop` int(10) unsigned DEFAULT '0',
  `disabled_pop2` varchar(25) DEFAULT NULL,
  `pct_pop_disabled` decimal(8,6) DEFAULT '0.000000',
  `pct_pop_disabled2` varchar(25) DEFAULT NULL,
  `quart_pct_disabled` smallint(6) NOT NULL DEFAULT '0',
  `_use` varchar(25) DEFAULT NULL,
  `audited` smallint(6) NOT NULL DEFAULT '0',
  `audited2` varchar(25) DEFAULT NULL,
  `audit_score_req_met` decimal(8,6) DEFAULT '0.000000',
  `audit_score_req_met2` varchar(25) DEFAULT NULL,
  `audit_score_best_prct_met` decimal(8,6) DEFAULT '0.000000',
  `audit_score_best_prct_met2` varchar(25) DEFAULT NULL,
  `retreival_method` varchar(25) DEFAULT NULL,
  `plan_year` smallint(6) NOT NULL DEFAULT '0',
  `plan_decade` varchar(20) DEFAULT 'Null',
  `retreival_year` smallint(6) NOT NULL DEFAULT '0',
  `plan_url` text,
  `ada_contact` text,
  `file_name` text,
  `file_location` text,
  `upload_date` date NOT NULL DEFAULT '1900-01-01',
  `active_record` smallint(6) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* before inserting updated records set active_record flag to zero for current records with accounts that are in this update */
     update wp_transition_plans trans
           join wp_transition_plans_districts dis on trans.district_id=dis.district_id join transition_plans_import_v imp on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county     
		      set active_record=0;

/* district_id, census_region, msa_urbanity, total_pop, total_pop2, pop_group, pop_group_max, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met, audit_score_req_met2, audit_score_best_prct_met, audit_score_best_prct_met2, retreival_method, plan_year, plan_decade, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date, active_record */


/* insert new records */
Insert into wp_transition_plans(district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met, audit_score_req_met2, audit_score_best_prct_met, audit_score_best_prct_met2, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date)
	  Select dis.district_id as district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met, audit_score_req_met2, audit_score_best_prct_met, audit_score_best_prct_met2, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date from transition_plans_import_v imp
Join wp_transition_plans_districts dis on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county;



/* Update these fields */
update wp_transition_plans
set audit_score_best_prct_met = .01*convert(replace(audit_score_best_prct_met2,"%",""), DECIMAL(10,4))
where active_record=1;

update wp_transition_plans
set audit_score_req_met = .01*convert(replace(audit_score_req_met2,"%",""), DECIMAL(10,4))
where active_record=1;

/* req_met_percentile, best_prct_percentile */ 
update wp_transition_plans
set req_met_percentile = if(audit_score_req_met<.25,'0-24th %tile', 
if(audit_score_req_met >= .25 and audit_score_req_met<.5, '25th-49th %tile', 
if(audit_score_req_met >= .5 and audit_score_req_met<.75,'50th-74th %tile', 
if(audit_score_req_met >= .75 and audit_score_req_met<=1,'75th–100th %tile', 'Out of this World!'))))
 where active_record=1;

update wp_transition_plans
set best_prct_percentile = if(audit_score_best_prct_met<.25,'0-24th %tile', 
if(audit_score_best_prct_met >= .25 and audit_score_best_prct_met<.5, '25th-49th %tile', 
if(audit_score_best_prct_met >= .5 and audit_score_best_prct_met<.75,'50th-74th %tile', 
if(audit_score_best_prct_met >= .75 and audit_score_best_prct_met<=1,'75th–100th %tile', 'Out of this World!'))))
 where active_record=1;

update wp_transition_plans
set plan_decade = if(plan_year<2000 and plan_year>1989,'1990s', 
if(plan_year<'2010' and plan_year>'1999', '2000s', 
if(plan_year<'2020' and plan_year>'2009','2010s', 
if(plan_year<'2030' and plan_year>'2019','2020s', '2030s'))))
 where active_record=1;

update wp_transition_plans
set pop_group = if(total_pop<10000,'Under 10,000', 
if(total_pop>=10000 and total_pop<20000, '10,000 to 19,999', 
if(total_pop>=20000 and total_pop<30000, '20,000 to 29,999', 
if(total_pop>=30000 and total_pop<40000, '30,000 to 39,999', 
if(total_pop>=40000 and total_pop<50000, '40,000 to 49,999', 
if(total_pop>=50000 and total_pop<100000, '50,000 to 99,999', 
if(total_pop>=100000 and total_pop<200000, '100,000 to 199,999', 
if(total_pop>=200000 and total_pop<1000000, '200,000 to 999,999', 
if(total_pop>=1000000 and total_pop<10000000, 'Over a million','Over 10 million')))))))))
 where active_record=1;

update wp_transition_plans
set pop_group_max = if(total_pop<10000,10000, 
if(total_pop>=10000 and total_pop<20000, 20000, 
if(total_pop>=20000 and total_pop<30000, 30000, 
if(total_pop>=30000 and total_pop<40000, 40000, 
if(total_pop>=40000 and total_pop<50000, 50000, 
if(total_pop>=50000 and total_pop<100000, 100000, 
if(total_pop>=100000 and total_pop<200000, 200000, 
if(total_pop>=200000 and total_pop<1000000, 1000000, 
if(total_pop>=1000000 and total_pop<10000000, 10000000, 20000000)))))))))
 where active_record=1;

/* A view for the website */
create view transition_plans_v as
Select dist.district_id as district_id, city_county, state_cd, unit_type, county, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, pop_group, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, 
audit_score_req_met, audit_score_req_met2, req_met_percentile, audit_score_best_prct_met, audit_score_best_prct_met2, best_prct_percentile, retreival_method, plan_year, plan_decade, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date, active_record from wp_transition_plans tp
join wp_transition_plans_districts dist on tp.district_id = dist.district_id where active_record=1;

use adagreatlakes01_db;

/* select statements for pop_group, plan_decade, etc filtering 
SELECT pop_group, count(*) FROM adagreatlakes01_db.wp_transition_plans group by pop_group, pop_group_max order by pop_group_max;


SELECT plan_decade, count(*) FROM adagreatlakes01_db.wp_transition_plans group by plan_decade order by plan_decade;

SELECT state_cd, count(*) as count FROM adagreatlakes01_db.transition_plans_v group by state_cd order by state_cd;
Select state_cd, count from (SELECT state_cd, count(*) as count FROM adagreatlakes01_db.transition_plans_v group by state_cd) AS totals where totals.count >2 order by totals.state_cd;



SELECT unit_type, count(*) as "# Records" FROM adagreatlakes01_db.transition_plans_v group by unit_type order by unit_type;

SELECT census_region, count(*) FROM adagreatlakes01_db.transition_plans_v group by census_region order by census_region;

SELECT income_group, count(*) FROM adagreatlakes01_db.transition_plans_v group by income_group order by income_group;

SELECT audit_quality, count(*) FROM adagreatlakes01_db.transition_plans_v group by audit_quality order by audit_quality;

delete from wp_transition_plans where active_record=1;

Select * from wp_transition_plans where active_record=1;

SELECT district_id, city_county, state_cd, unit_type, county, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, pop_group, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met, audit_score_req_met2, req_met_percentile, audit_score_best_prct_met, audit_score_best_prct_met2, best_prct_percentile, retreival_method, plan_year, plan_decade, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date, active_record FROM transition_plans_v where best_prct_percentile ='Out of this World!' Order by state_cd, city_county, unit_type;

SELECT best_prct_percentile, count(*) as 'count' FROM transition_plans_v group by best_prct_percentile order by best_prct_percentile;

SELECT req_met_percentile, count(*) as 'count' FROM transition_plans_v group by req_met_percentile order by req_met_percentile;

*/

/******************************************/
/******************************************/
/******************************************/
/* Transition Plan tables */
/******************************************/
/******************************************/
/******************************************/

/* currently not being used */
CREATE TABLE `wp_transition_plans_parameters` (
  `upload_date` date DEFAULT NULL,
  `upload_file` varchar(50) DEFAULT NULL,
  `upload_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`upload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

Insert into wp_transition_plans_parameters(upload_date, upload_file)
VALUES("2021-12-16", "TP_Database_update.xlsx");




SELECT * FROM adagreatlakes03_db.wp_online_resource_repository_import;
SELECT text, url FROM wp_online_resource_repository_import;
SELECT text, url FROM wp_online_resource_repository_import;
SELECT * FROM adagreatlakes03_db.wp_online_resource_repository_import;
SELECT text, url, if(instr(url, "www.")>0, 1, if(instr(url, "http")>0, 1, 0)) as is_url  FROM adagreatlakes03_db.wp_online_resource_repository_import where url="";

SELECT * FROM adagreatlakes97_db.transition_plans_v;
select district_id, city_county, state_cd, unit_type, county, total_pop, disabled_pop, disabled_pop2, (disabled_pop/total_pop) as pct_pop_disabled_calc, pct_pop_disabled, (disabled_pop/total_pop)-pct_pop_disabled as pct_pop_disabled_diff, pct_pop_disabled2 from transition_plans_v order by (disabled_pop/total_pop)-pct_pop_disabled;

select district_id, city_county, state_cd, unit_type, county, total_pop, pct_pop_disabled, cast(total_pop*pct_pop_disabled as signed) as disabled_pop_calc, disabled_pop, cast(total_pop*pct_pop_disabled as signed)-disabled_pop as disabled_pop_diff 
From transition_plans_v order by (total_pop*pct_pop_disabled)-disabled_pop;


select district_id, city_county, state_cd, unit_type, county, total_pop, pct_pop_disabled, total_pop*pct_pop_disabled as disabled_pop_calc, disabled_pop, (total_pop*pct_pop_disabled)-disabled_pop as disabled_pop_diff 
From transition_plans_v order by (total_pop*pct_pop_disabled)-disabled_pop;



/* 11:04:54	select district_id, city_county, state_cd, unit_type, county, total_pop, pct_pop_disabled, cast(total_pop*pct_pop_disabled as signed) as disabled_pop_calc, disabled_pop, cast(total_pop*pct_pop_disabled as signed)-disabled_pop as disabled_pop_diff  From transition_plans_v order by (total_pop*pct_pop_disabled)-disabled_pop	Error Code: 1690. BIGINT UNSIGNED value is out of range in '(cast((`adagreatlakes97_db`.`tp`.`total_pop` * `adagreatlakes97_db`.`tp`.`pct_pop_disabled`) as signed) - `adagreatlakes97_db`.`tp`.`disabled_pop`)'	0.000 sec */

use adagreatlakes01_db;

select tp2.city_county, tp2.state_cd, tp2.unit_type, tp2.county from transition_plans_import2_v tp2 join  transition_plans_import_v tp1 on tp2.city_county = tp1.city_county and tp2.state_cd = tp1.state_cd and tp2.unit_type = tp1.unit_type;


/* CITY/COUNTY, STATE, UNIT TYPE, COUNTY, CENSUS REGION, MSA URBANITY, TOTAL POPULATION, QUARTPOP, MEDIAN HH INCOME, INCOME GROUP, POVERTY RATE, QUARTPOVERTY, MEDIAN AGE, % OF POP. 65 & OVER, QUART % 65 & OVER, DISABLED POPULATION, % OF TOTAL POP. DISABLED, QUART%DISABLED, USE, AUDITED, AUDIT SCORE- REQUIRMENTS MET, AUDIT SCORE- BEST/GOOD PRACTICE MET, RETREIVAL METHOD, YEAR OF MOST RECENT PLAN, RETREIVAL  YEAR, URL, ADA CONTACT, FILE NAME, FILE LOCATION, MyUnknownColumn*/

create view transition_plans_import2_v as 
 SELECT `wp_transition_plans_import2`.`CITY/COUNTY` as city_county,
 `wp_transition_plans_import2`.`STATE`as state_cd,
 `wp_transition_plans_import2`.`UNIT TYPE` as unit_type,
 CONCAT(UCASE(LEFT(`wp_transition_plans_import2`.`COUNTY`, 1)),lower(SUBSTRING(`wp_transition_plans_import2`.`COUNTY`, 2))) as county,
 `wp_transition_plans_import2`.`CENSUS REGION` as census_region,
 lower(`wp_transition_plans_import2`.`MSA URBANITY`) as msa_urbanity,
 convert(replace(`wp_transition_plans_import2`.`TOTAL POPULATION`,",",""), DECIMAL(7,0)) as total_pop,
 `wp_transition_plans_import2`.`TOTAL POPULATION` as total_pop2,
 `wp_transition_plans_import2`.`QUARTPOP` as quart_pop,
 convert(replace(replace(`wp_transition_plans_import2`.`MEDIAN HH INCOME`,"$",""),",",""), DECIMAL(7,0)) as median_hh_income,
 `wp_transition_plans_import2`.`MEDIAN HH INCOME` as median_hh_income2,
 lower(trim(`wp_transition_plans_import2`.`INCOME GROUP`)) as income_group,
 .01*convert(`wp_transition_plans_import2`.`POVERTY RATE`, DECIMAL(4,2)) as poverty_rate,
 `wp_transition_plans_import2`.`POVERTY RATE` as poverty_rate2,
 `wp_transition_plans_import2`.`QUARTPOVERTY` as quart_poverty,
 `wp_transition_plans_import2`.`MEDIAN AGE` as median_age, 
 .01*convert(`wp_transition_plans_import2`.`% OF POP. 65 & OVER`, DECIMAL(4,2)) as pct_pop_senior,
 `wp_transition_plans_import2`.`% OF POP. 65 & OVER` as pct_pop_senior2,
 `wp_transition_plans_import2`.`QUART % 65 & OVER` as quart_pct_senior,
 convert(replace(`wp_transition_plans_import2`.`DISABLED POPULATION`,",",""), DECIMAL(7,0)) as disabled_pop,
 `wp_transition_plans_import2`.`DISABLED POPULATION` as disabled_pop2,
 .01*convert(`wp_transition_plans_import2`.`% OF TOTAL POP. DISABLED`, DECIMAL(4,2)) as pct_pop_disabled,
 `wp_transition_plans_import2`.`% OF TOTAL POP. DISABLED` as pct_pop_disabled2,
 `wp_transition_plans_import2`.`QUART%DISABLED` as quart_pct_disabled,
 `wp_transition_plans_import2`.`USE` as _use,
 IF(`wp_transition_plans_import2`.`AUDITED`='Yes', 1, 0) as audited,
 `wp_transition_plans_import2`.`AUDITED` as audited2,
 .01*convert(`wp_transition_plans_import2`.`AUDIT SCORE- REQUIRMENTS MET`, DECIMAL(4,2)) as audit_score_req_met,
 `wp_transition_plans_import2`.`AUDIT SCORE- REQUIRMENTS MET` as audit_score_req_met2,
 .01*convert(`wp_transition_plans_import2`.`AUDIT SCORE- BEST/GOOD PRACTICE MET`, DECIMAL(4,2)) as audit_score_best_prct_met,
 `wp_transition_plans_import2`.`AUDIT SCORE- BEST/GOOD PRACTICE MET` as audit_score_best_prct_met2,
 `wp_transition_plans_import2`.`RETREIVAL METHOD` as retreival_method,
 `wp_transition_plans_import2`.`YEAR OF MOST RECENT PLAN` as plan_year,
 `wp_transition_plans_import2`.`RETREIVAL  YEAR` as retreival_year,
 `wp_transition_plans_import2`.`URL` as plan_url,
 `wp_transition_plans_import2`.`ADA CONTACT` as ada_contact,
 `wp_transition_plans_import2`.`FILE NAME` as file_name,
 `wp_transition_plans_import2`.`FILE LOCATION` as file_location,
 date(now()) as upload_date
 FROM `wp_transition_plans_import2`;





CREATE TABLE `wp_transition_plans_import22` (
  `CITY/COUNTY` text,
  `STATE` text,
  `UNIT TYPE` text,
  `COUNTY` text,
  `CENSUS REGION` text,
  `MSA URBANITY` text,
  `TOTAL POPULATION` text,
  `QUARTPOP` int(11) DEFAULT NULL,
  `MEDIAN HH INCOME` text,
  `INCOME GROUP` text,
  `POVERTY RATE` text,
  `QUARTPOVERTY` int(11) DEFAULT NULL,
  `MEDIAN AGE` double DEFAULT NULL,
  `% OF POP. 65 & OVER` text,
  `QUART % 65 & OVER` int(11) DEFAULT NULL,
  `DISABLED POPULATION` text,
  `% OF TOTAL POP. DISABLED` text,
  `QUART%DISABLED` int(11) DEFAULT NULL,
  `USE` text,
  `AUDITED` text,
  `AUDIT SCORE- REQUIRMENTS MET` text,
  `AUDIT SCORE- BEST/GOOD PRACTICE MET` text,
  `RETREIVAL METHOD` text,
  `YEAR OF MOST RECENT PLAN` int(11) DEFAULT NULL,
  `RETREIVAL  YEAR` int(11) DEFAULT NULL,
  `URL` text,
  `ADA CONTACT` text,
  `FILE NAME` text,
  `FILE LOCATION` text,
  `MyUnknownColumn` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


Insert into wp_transition_plans(district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met, audit_score_req_met2, audit_score_best_prct_met, audit_score_best_prct_met2, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date)
	  Select dis.district_id as district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met, audit_score_req_met2, audit_score_best_prct_met, audit_score_best_prct_met2, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date from transition_plans_import_v imp
Join wp_transition_plans_districts dis on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county;


Insert into wp_transition_plans(district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met2, audit_score_best_prct_met2, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date)
	  Select dis.district_id as district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met2, audit_score_best_prct_met2, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date from transition_plans_import_v imp
Join wp_transition_plans_districts dis on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county;

	  Select dis.district_id as district_id, census_region, msa_urbanity, audit_score_req_met, audit_score_best_prct_met, audit_score_req_met2, audit_score_best_prct_met2, upload_date from transition_plans_import_v imp
Join wp_transition_plans_districts dis on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county;






/* poverty rate */
SELECT min(poverty_rate), quart_poverty, max(poverty_rate) FROM adagreatlakes01_db.transition_plans_v group by quart_poverty order by quart_poverty;
SELECT city_county, state_cd, unit_type, poverty_rate, poverty_rate2, quart_poverty FROM adagreatlakes01_db.transition_plans_v order by quart_poverty, poverty_rate;

/* total population */
SELECT min(total_pop), quart_pop, max(total_pop) FROM adagreatlakes01_db.transition_plans_v group by quart_pop order by quart_pop;
SELECT city_county, state_cd, unit_type, total_pop, total_pop2, quart_pop FROM adagreatlakes01_db.transition_plans_v order by quart_pop, total_pop;
SELECT city_county, state_cd, unit_type, total_pop, total_pop2, quart_pop, pop_group FROM adagreatlakes01_db.transition_plans_v order by quart_pop, total_pop;


/* % Senior */
SELECT min(pct_pop_senior), quart_pct_senior, max(pct_pop_senior) FROM adagreatlakes01_db.transition_plans_v group by quart_pct_senior order by quart_pct_senior;

/* pct_pop_disabled */
SELECT min(pct_pop_disabled), quart_pct_disabled, max(pct_pop_disabled) FROM adagreatlakes01_db.transition_plans_v group by quart_pct_disabled order by quart_pct_disabled;
SELECT city_county, state_cd, unit_type, total_pop, total_pop2, quart_pop, pop_group FROM adagreatlakes01_db.transition_plans_v order by quart_pop, total_pop;

/* district_id, city_county, state_cd, unit_type, county, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, pop_group, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, plan_decade, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date, active_record */

SELECT city_county, state_cd, unit_type, poverty_rate, poverty_rate2, quart_poverty FROM adagreatlakes01_db.transition_plans_v order by quart_poverty, poverty_rate;

SELECT `district_id`, `city_county`, `state_cd`, `unit_type`, `county`, `census_region`, `msa_urbanity`, `total_pop`, `quart_pop`, `median_hh_income`, `income_group`, `poverty_rate`, `quart_poverty`, `median_age`, `pct_pop_senior`, `quart_pct_senior`, `disabled_pop`, `pct_pop_disabled`, `quart_pct_disabled`, `_use`, `audited`, `audit_quality`, `retreival_method`, `plan_year`, `retreival_year`, `plan_url`, `ada_contact`, `file_name`, `file_location` FROM `transition_plans_v` where income_group='upper' Order by state_cd, city_county, unit_type;


/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/




CREATE TABLE `wp_transition_plans` (
  `district_id` int(11) NOT NULL,  
  `census_region` varchar(25) DEFAULT NULL,
  `msa_urbanity` varchar(45) DEFAULT NULL,
  `total_pop` int(10) unsigned DEFAULT '0',
  `total_pop2` varchar(25) DEFAULT NULL,  
  `quart_pop`  smallint(6) NOT NULL DEFAULT '0',
  `pop_group` varchar(20) DEFAULT NULL,
  `pop_group_max` int(10) unsigned DEFAULT '0',
  `median_hh_income` int(10) unsigned DEFAULT '0',
  `median_hh_income2` varchar(25) DEFAULT NULL,
  `income_group` varchar(25) DEFAULT NULL,
  `poverty_rate` decimal(8,6) DEFAULT '0.00',
  `poverty_rate2` varchar(25) DEFAULT NULL,
  `quart_poverty` smallint(6) NOT NULL DEFAULT '0',
  `median_age` double DEFAULT NULL DEFAULT '0',
  `pct_pop_senior` decimal(8,6) DEFAULT '0.00',
  `pct_pop_senior2` varchar(25) DEFAULT NULL,
  `quart_pct_senior` smallint(6) NOT NULL DEFAULT '0',
  `disabled_pop` int(10) unsigned DEFAULT '0',
  `disabled_pop2`    varchar(25) DEFAULT NULL,
  `pct_pop_disabled` decimal(8,6) DEFAULT '0.00',
  `pct_pop_disabled2`  varchar(25) DEFAULT NULL,
  `quart_pct_disabled` smallint(6) NOT NULL DEFAULT '0',
  `_use` varchar(25) DEFAULT NULL,
  `audited` smallint(6) NOT NULL DEFAULT '0',
  `audited2` varchar(25) DEFAULT NULL,
  `audit_quality` varchar(25) DEFAULT NULL,
  `retreival_method` varchar(25) DEFAULT NULL,
  `plan_year` smallint(6) NOT NULL DEFAULT '0',
  `plan_decade` varchar(20) DEFAULT NULL,
  `retreival_year` smallint(6) NOT NULL DEFAULT '0',
  `plan_url` text,
  `ada_contact` text,
  `file_name` text,
  `file_location` text,
  `upload_date` date NOT NULL default '1900-01-01',
  `active_record` smallint(6) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* before inserting updated records set active_record flag to zero for current records with accounts that are in this update */
     update wp_transition_plans trans
           join wp_transition_plans_districts dis on trans.district_id=dis.district_id join transition_plans_import_v imp on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county     
		      set active_record=0;

/* insert new records */
Insert into wp_transition_plans(district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date)
       Select dis.district_id as district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date from transition_plans_import_v imp
Join wp_transition_plans_districts dis on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county; 


/* Update these fields */

update wp_transition_plans
set plan_decade = if(plan_year<2000 and plan_year>1989,'1990s', 
if(plan_year<'2010' and plan_year>'1999', '2000s', 
if(plan_year<'2020' and plan_year>'2009','2010s', 
if(plan_year<'2030' and plan_year>'2019','2020s', '2030s'))))
 where active_record=1;

update wp_transition_plans
set pop_group_max = if(total_pop<10000,10000, 
if(total_pop>=10000 and total_pop<20000, 20000, 
if(total_pop>=20000 and total_pop<30000, 30000, 
if(total_pop>=30000 and total_pop<40000, 40000, 
if(total_pop>=40000 and total_pop<50000, 50000, 
if(total_pop>=50000 and total_pop<100000, 100000, 
if(total_pop>=100000 and total_pop<200000, 200000, 
if(total_pop>=200000 and total_pop<1000000, 1000000, 
if(total_pop>=1000000 and total_pop<10000000, 10000000, 20000000)))))))))
 where active_record=1;




/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/



/* poverty rate */
SELECT min(poverty_rate), quart_poverty, max(poverty_rate) FROM adagreatlakes01_db.transition_plans_v group by quart_poverty order by quart_poverty;
SELECT city_county, state_cd, unit_type, poverty_rate, poverty_rate2, quart_poverty FROM adagreatlakes01_db.transition_plans_v order by quart_poverty, poverty_rate;

/* total population */
SELECT min(total_pop), quart_pop, max(total_pop) FROM adagreatlakes01_db.transition_plans_v group by quart_pop order by quart_pop;
SELECT city_county, state_cd, unit_type, total_pop, total_pop2, quart_pop FROM adagreatlakes01_db.transition_plans_v order by quart_pop, total_pop;
SELECT city_county, state_cd, unit_type, total_pop, total_pop2, quart_pop, pop_group FROM adagreatlakes01_db.transition_plans_v order by quart_pop, total_pop;


/* % Senior */
SELECT min(pct_pop_senior), quart_pct_senior, max(pct_pop_senior) FROM adagreatlakes01_db.transition_plans_v group by quart_pct_senior order by quart_pct_senior;

/* pct_pop_disabled */
SELECT min(pct_pop_disabled), quart_pct_disabled, max(pct_pop_disabled) FROM adagreatlakes01_db.transition_plans_v group by quart_pct_disabled order by quart_pct_disabled;
SELECT city_county, state_cd, unit_type, total_pop, total_pop2, quart_pop, pop_group FROM adagreatlakes01_db.transition_plans_v order by quart_pop, total_pop;

/* district_id, city_county, state_cd, unit_type, county, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, pop_group, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, plan_decade, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date, active_record */

SELECT city_county, state_cd, unit_type, poverty_rate, poverty_rate2, quart_poverty FROM adagreatlakes01_db.transition_plans_v order by quart_poverty, poverty_rate;

SELECT `district_id`, `city_county`, `state_cd`, `unit_type`, `county`, `census_region`, `msa_urbanity`, `total_pop`, `quart_pop`, `median_hh_income`, `income_group`, `poverty_rate`, `quart_poverty`, `median_age`, `pct_pop_senior`, `quart_pct_senior`, `disabled_pop`, `pct_pop_disabled`, `quart_pct_disabled`, `_use`, `audited`, `audit_quality`, `retreival_method`, `plan_year`, `retreival_year`, `plan_url`, `ada_contact`, `file_name`, `file_location` FROM `transition_plans_v` where income_group='upper' Order by state_cd, city_county, unit_type;



/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/


/* poverty rate */
SELECT min(poverty_rate), quart_poverty, max(poverty_rate) FROM adagreatlakes01_db.transition_plans_v group by quart_poverty order by quart_poverty;
SELECT city_county, state_cd, unit_type, poverty_rate, poverty_rate2, quart_poverty FROM adagreatlakes01_db.transition_plans_v order by quart_poverty, poverty_rate;

/* total population */
SELECT min(total_pop), quart_pop, max(total_pop) FROM adagreatlakes01_db.transition_plans_v group by quart_pop order by quart_pop;
SELECT city_county, state_cd, unit_type, total_pop, total_pop2, quart_pop FROM adagreatlakes01_db.transition_plans_v order by quart_pop, total_pop;
SELECT city_county, state_cd, unit_type, total_pop, total_pop2, quart_pop, pop_group FROM adagreatlakes01_db.transition_plans_v order by quart_pop, total_pop;


/* % Senior */
SELECT min(pct_pop_senior), quart_pct_senior, max(pct_pop_senior) FROM adagreatlakes01_db.transition_plans_v group by quart_pct_senior order by quart_pct_senior;

/* pct_pop_disabled */
SELECT min(pct_pop_disabled), quart_pct_disabled, max(pct_pop_disabled) FROM adagreatlakes01_db.transition_plans_v group by quart_pct_disabled order by quart_pct_disabled;
SELECT city_county, state_cd, unit_type, total_pop, total_pop2, quart_pop, pop_group FROM adagreatlakes01_db.transition_plans_v order by quart_pop, total_pop;

/* district_id, city_county, state_cd, unit_type, county, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, pop_group, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, plan_decade, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date, active_record */

SELECT city_county, state_cd, unit_type, poverty_rate, poverty_rate2, quart_poverty FROM adagreatlakes01_db.transition_plans_v order by quart_poverty, poverty_rate;

SELECT `district_id`, `city_county`, `state_cd`, `unit_type`, `county`, `census_region`, `msa_urbanity`, `total_pop`, `quart_pop`, `median_hh_income`, `income_group`, `poverty_rate`, `quart_poverty`, `median_age`, `pct_pop_senior`, `quart_pct_senior`, `disabled_pop`, `pct_pop_disabled`, `quart_pct_disabled`, `_use`, `audited`, `audit_quality`, `retreival_method`, `plan_year`, `retreival_year`, `plan_url`, `ada_contact`, `file_name`, `file_location` FROM `transition_plans_v` where income_group='upper' Order by state_cd, city_county, unit_type;




/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/




CREATE TABLE `wp_transition_plans` (
  `district_id` int(11) NOT NULL,  
  `census_region` varchar(25) DEFAULT NULL,
  `msa_urbanity` varchar(45) DEFAULT NULL,
  `total_pop` int(10) unsigned DEFAULT '0',
  `total_pop2` varchar(25) DEFAULT NULL,  
  `quart_pop`  smallint(6) NOT NULL DEFAULT '0',
  `pop_group` varchar(20) DEFAULT NULL,
  `pop_group_max` int(10) unsigned DEFAULT '0',
  `median_hh_income` int(10) unsigned DEFAULT '0',
  `median_hh_income2` varchar(25) DEFAULT NULL,
  `income_group` varchar(25) DEFAULT NULL,
  `poverty_rate` decimal(8,6) DEFAULT '0.00',
  `poverty_rate2` varchar(25) DEFAULT NULL,
  `quart_poverty` smallint(6) NOT NULL DEFAULT '0',
  `median_age` double DEFAULT NULL DEFAULT '0',
  `pct_pop_senior` decimal(8,6) DEFAULT '0.00',
  `pct_pop_senior2` varchar(25) DEFAULT NULL,
  `quart_pct_senior` smallint(6) NOT NULL DEFAULT '0',
  `disabled_pop` int(10) unsigned DEFAULT '0',
  `disabled_pop2`    varchar(25) DEFAULT NULL,
  `pct_pop_disabled` decimal(8,6) DEFAULT '0.00',
  `pct_pop_disabled2`  varchar(25) DEFAULT NULL,
  `quart_pct_disabled` smallint(6) NOT NULL DEFAULT '0',
  `_use` varchar(25) DEFAULT NULL,
  `audited` smallint(6) NOT NULL DEFAULT '0',
  `audited2` varchar(25) DEFAULT NULL,
  `audit_quality` varchar(25) DEFAULT NULL,
  `retreival_method` varchar(25) DEFAULT NULL,
  `plan_year` smallint(6) NOT NULL DEFAULT '0',
  `plan_decade` varchar(20) DEFAULT NULL,
  `retreival_year` smallint(6) NOT NULL DEFAULT '0',
  `plan_url` text,
  `ada_contact` text,
  `file_name` text,
  `file_location` text,
  `upload_date` date NOT NULL default '1900-01-01',
  `active_record` smallint(6) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* before inserting updated records set active_record flag to zero for current records with accounts that are in this update */
     update wp_transition_plans trans
           join wp_transition_plans_districts dis on trans.district_id=dis.district_id join transition_plans_import_v imp on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county     
		      set active_record=0;

/* insert new records */
Insert into wp_transition_plans(district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date)
       Select dis.district_id as district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date from transition_plans_import_v imp
Join wp_transition_plans_districts dis on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county; 


/* Update these fields */

update wp_transition_plans
set plan_decade = if(plan_year<2000 and plan_year>1989,'1990s', 
if(plan_year<'2010' and plan_year>'1999', '2000s', 
if(plan_year<'2020' and plan_year>'2009','2010s', 
if(plan_year<'2030' and plan_year>'2019','2020s', '2030s'))))
 where active_record=1;

update wp_transition_plans
set pop_group_max = if(total_pop<10000,10000, 
if(total_pop>=10000 and total_pop<20000, 20000, 
if(total_pop>=20000 and total_pop<30000, 30000, 
if(total_pop>=30000 and total_pop<40000, 40000, 
if(total_pop>=40000 and total_pop<50000, 50000, 
if(total_pop>=50000 and total_pop<100000, 100000, 
if(total_pop>=100000 and total_pop<200000, 200000, 
if(total_pop>=200000 and total_pop<1000000, 1000000, 
if(total_pop>=1000000 and total_pop<10000000, 10000000, 20000000)))))))))
 where active_record=1;





/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/


SELECT if(plan_year<2000 and plan_year>1989,'1990s', if(plan_year<'2010' and plan_year>'1999', '2000s', if(plan_year<'2020' and plan_year>'2009','2010s', if(plan_year<'2030' and plan_year>'2019','2020s', '2030s')))) as decade, count(1) as numRecs  FROM adagreatlakes01_db.wp_transition_plans where active_record=1 group by if(plan_year<2000 and plan_year>1989,'1990''s', if(plan_year<'2010' and plan_year>'1999', '2000''s', if(plan_year<'2020' and plan_year>'2009','2010''s', if(plan_year<'2030' and plan_year>'2019','2020''s', '2030''s'))));

/* if(plan_year<2000 and plan_year>1989,'1990s', if(plan_year<'2010' and plan_year>'1999', '2000s', if(plan_year<'2020' and plan_year>'2009','2010s', if(plan_year<'2030' and plan_year>'2019','2020s', '2030s')))) as plan_decade */


SELECT state_cd, count(1) as numRecs FROM adagreatlakes01_db.transition_plans_v group by state_cd order by state_cd;

select city_county, total_pop from adagreatlakes01_db.transition_plans_v order by total_pop;

/* 	population_group
   'Under 10,000'
   '10,000 to 19,999'
   '20,000 to 29,999'
   '30,000 to 39,999'
   '40,000 to 49,999'
   '50,000 to 99,999'
   '100,000 to 199,999'
   '100,000 to 199,999'
   '200,000 to 999,999'
   'over a million'
   
   if(total_pop<10000,'Under 10,000', if(total_pop>=10000 and total_pop<20000, '10,000 to 19,999', if(total_pop>=20000 and total_pop<30000, '20,000 to 29,999', if(total_pop>=30000 and total_pop<40000, '30,000 to 39,999', 
   if(total_pop>=40000 and total_pop<50000, '40,000 to 49,999', if(total_pop>=50000 and total_pop<100000, '50,000 to 99,999', if(total_pop>=100000 and total_pop<200000, '100,000 to 199,999', if(total_pop>=200000 and total_pop<1000000, '200,000 to 999,999', if(total_pop>=1000000 and total_pop<10000000, 'Over a million','Over 10 million')))))) as pop_group
   
*/
   
   


/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/



use bonkaroo60_db;

CREATE TABLE `aiw_transition_plans_import` (
  `CITY/COUNTY` text,
  `STATE` text,
  `UNIT TYPE` text,
  `COUNTY` text,
  `CENSUS REGION` text,
  `MSA URBANITY` text,
  `TOTAL POPULATION` text,
  `QUARTPOP` int(11) DEFAULT NULL,
  `MEDIAN HH INCOME` text,
  `INCOME GROUP` text,
  `POVERTY RATE` text,
  `QUARTPOVERTY` int(11) DEFAULT NULL,
  `MEDIAN AGE` double DEFAULT NULL,
  `% OF POP. 65 & OVER` text,
  `QUART % 65 & OVER` int(11) DEFAULT NULL,
  `DISABLED POPULATION` text,
  `% OF TOTAL POP. DISABLED` text,
  `QUART%DISABLED` int(11) DEFAULT NULL,
  `USE` text,
  `AUDITED` text,
  `AUDIT QUALITY SCORE` text,
  `RETREIVAL METHOD` text,
  `YEAR OF MOST RECENT PLAN` int(11) DEFAULT NULL,
  `RETREIVAL  YEAR` int(11) DEFAULT NULL,
  `URL` text,
  `ADA CONTACT` text,
  `FILE NAME` text,
  `FILE LOCATION` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


create view transition_plans_import_v as 
 SELECT `aiw_transition_plans_import`.`CITY/COUNTY` as city_county,
 `aiw_transition_plans_import`.`STATE`as state_cd,
 `aiw_transition_plans_import`.`UNIT TYPE` as unit_type,
 CONCAT(UCASE(LEFT(`aiw_transition_plans_import`.`COUNTY`, 1)),lower(SUBSTRING(`aiw_transition_plans_import`.`COUNTY`, 2))) as county,
 `aiw_transition_plans_import`.`CENSUS REGION` as census_region,
 lower(`aiw_transition_plans_import`.`MSA URBANITY`) as msa_urbanity,
 convert(replace(`aiw_transition_plans_import`.`TOTAL POPULATION`,",",""), DECIMAL(7,0)) as total_pop,
 `aiw_transition_plans_import`.`TOTAL POPULATION` as total_pop2,
 `aiw_transition_plans_import`.`QUARTPOP` as quart_pop,
 convert(replace(replace(`aiw_transition_plans_import`.`MEDIAN HH INCOME`,"$",""),",",""), DECIMAL(7,0)) as median_hh_income,
 `aiw_transition_plans_import`.`MEDIAN HH INCOME` as median_hh_income2,
 lower(trim(`aiw_transition_plans_import`.`INCOME GROUP`)) as income_group,
 .01*convert(`aiw_transition_plans_import`.`POVERTY RATE`, DECIMAL(4,2)) as poverty_rate,
 `aiw_transition_plans_import`.`POVERTY RATE` as poverty_rate2,
 `aiw_transition_plans_import`.`QUARTPOVERTY` as quart_poverty,
 `aiw_transition_plans_import`.`MEDIAN AGE` as median_age, 
 .01*convert(`aiw_transition_plans_import`.`% OF POP. 65 & OVER`, DECIMAL(4,2)) as pct_pop_senior,
 `aiw_transition_plans_import`.`% OF POP. 65 & OVER` as pct_pop_senior2,
 `aiw_transition_plans_import`.`QUART % 65 & OVER` as quart_pct_senior,
 convert(replace(`aiw_transition_plans_import`.`DISABLED POPULATION`,",",""), DECIMAL(7,0)) as disabled_pop,
 `aiw_transition_plans_import`.`DISABLED POPULATION` as disabled_pop2,
 .01*convert(`aiw_transition_plans_import`.`% OF TOTAL POP. DISABLED`, DECIMAL(4,2)) as pct_pop_disabled,
 `aiw_transition_plans_import`.`% OF TOTAL POP. DISABLED` as pct_pop_disabled2,
 `aiw_transition_plans_import`.`QUART%DISABLED` as quart_pct_disabled,
 `aiw_transition_plans_import`.`USE` as _use,
 IF(`aiw_transition_plans_import`.`AUDITED`='Yes', 1, 0) as audited,
 `aiw_transition_plans_import`.`AUDITED` as audited2,
 `aiw_transition_plans_import`.`AUDIT QUALITY SCORE` as audit_quality,
 `aiw_transition_plans_import`.`RETREIVAL METHOD` as retreival_method,
 `aiw_transition_plans_import`.`YEAR OF MOST RECENT PLAN` as plan_year,
 `aiw_transition_plans_import`.`RETREIVAL  YEAR` as retreival_year,
 `aiw_transition_plans_import`.`URL` as plan_url,
 `aiw_transition_plans_import`.`ADA CONTACT` as ada_contact,
 `aiw_transition_plans_import`.`FILE NAME` as file_name,
 `aiw_transition_plans_import`.`FILE LOCATION` as file_location,
 date(now()) as upload_date
 FROM `bonkaroo60_db`.`aiw_transition_plans_import`;


CREATE TABLE `aiw_transition_plans` (
  `district_id` int(11) NOT NULL,  
  `census_region` varchar(25) DEFAULT NULL,
  `msa_urbanity` varchar(45) DEFAULT NULL,
  `total_pop` int(10) unsigned DEFAULT '0',
  `total_pop2` varchar(25) DEFAULT NULL,  
  `quart_pop`  smallint(6) NOT NULL DEFAULT '0',
  `median_hh_income` int(10) unsigned DEFAULT '0',
  `median_hh_income2` varchar(25) DEFAULT NULL,
  `income_group` varchar(25) DEFAULT NULL,
  `poverty_rate` decimal(8,6) DEFAULT '0.00',
  `poverty_rate2` varchar(25) DEFAULT NULL,
  `quart_poverty` smallint(6) NOT NULL DEFAULT '0',
  `median_age` double DEFAULT NULL DEFAULT '0',
  `pct_pop_senior` decimal(8,6) DEFAULT '0.00',
  `pct_pop_senior2` varchar(25) DEFAULT NULL,
  `quart_pct_senior` smallint(6) NOT NULL DEFAULT '0',
  `disabled_pop` int(10) unsigned DEFAULT '0',
  `disabled_pop2`    varchar(25) DEFAULT NULL,
  `pct_pop_disabled` decimal(8,6) DEFAULT '0.00',
  `pct_pop_disabled2`  varchar(25) DEFAULT NULL,
  `quart_pct_disabled` smallint(6) NOT NULL DEFAULT '0',
  `_use` varchar(25) DEFAULT NULL,
  `audited` smallint(6) NOT NULL DEFAULT '0',
  `audited2` varchar(25) DEFAULT NULL,
  `audit_quality` varchar(25) DEFAULT NULL,
  `retreival_method` varchar(25) DEFAULT NULL,
  `plan_year` smallint(6) NOT NULL DEFAULT '0',
  `retreival_year` smallint(6) NOT NULL DEFAULT '0',
  `plan_url` text,
  `ada_contact` text,
  `file_name` text,
  `file_location` text,
  `upload_date` date NOT NULL default '1900-01-01',
  `active_record` smallint(6) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* before inserting updated records set active_record flag to zero for current records with accounts that are in this update */
     update aiw_transition_plans trans
           join aiw_transition_plans_districts dis on trans.district_id=dis.district_id join transition_plans_import_v imp on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county     
		      set active_record=0;

/* insert new records */
Insert into aiw_transition_plans(district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date)
       Select dis.district_id as district_id, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date from transition_plans_import_v imp
Join aiw_transition_plans_districts dis on imp.city_county=dis.city_county and imp.state_cd=dis.state_cd and imp.unit_type=dis.unit_type and imp.county=dis.county; 



CREATE TABLE `aiw_transition_plans_districts` (
  `city_county` varchar(50) DEFAULT NULL,
  `state_cd` varchar(5) DEFAULT NULL,
  `unit_type` varchar(25) DEFAULT NULL,
  `county` varchar(50) DEFAULT NULL,
  `district_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
Insert into aiw_transition_plans_districts(city_county, state_cd, unit_type, county)
select city_county, state_cd, unit_type, county from transition_plans_import_v order by state_cd, unit_type, city_county;
*/


/* Import only new district records */
Insert into aiw_transition_plans_districts(city_county, state_cd, unit_type, county)
Select imp.city_county, imp.state_cd, imp.unit_type, imp.county from transition_plans_import_v imp left join aiw_transition_plans_districts dist on imp.city_county=dist.city_county and imp.state_cd=dist.state_cd and imp.unit_type=dist.unit_type and imp.county=dist.county where dist.district_id is null order by state_cd, county, city_county;

/* List district records that are not included in this new imported set */
Select dist.district_id, dist.city_county, dist.state_cd, dist.unit_type, dist.county from transition_plans_import_v imp right join aiw_transition_plans_districts dist on imp.city_county=dist.city_county and imp.state_cd=dist.state_cd and imp.unit_type=dist.unit_type and imp.county=dist.county where imp.census_region is null order by state_cd, county, city_county;


/* Select record and now a view for the website */
create view transition_plans_v as
Select dist.district_id as district_id, city_county, state_cd, unit_type, county, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_quality, retreival_method, plan_year, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date, active_record from aiw_transition_plans tp
join aiw_transition_plans_districts dist on tp.district_id = dist.district_id where active_record=1;


/* currently not being used */
CREATE TABLE `aiw_transition_plans_parameters` (
  `upload_date` date DEFAULT NULL,
  `upload_file` varchar(50) DEFAULT NULL,
  `upload_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`upload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

Insert into aiw_transition_plans_parameters(upload_date, upload_file)
VALUES("2021-12-16", "TP_Database_update.xlsx");









SELECT * FROM bonkaroo60_db.transition_plans_import_v;

Select dist.district_id, dist.city_county, dist.state_cd, dist.unit_type, dist.county from transition_plans_import_v imp right join aiw_transition_plans_districts dist on imp.city_county=dist.city_county and imp.state_cd=dist.state_cd and imp.unit_type=dist.unit_type and imp.county=dist.county where imp.census_region is null order by state_cd, county, city_county;
Select imp.city_county, imp.state_cd, imp.unit_type, imp.county from transition_plans_import_v imp left join aiw_transition_plans_districts dist on imp.city_county=dist.city_county and imp.state_cd=dist.state_cd and imp.unit_type=dist.unit_type and imp.county=dist.county where dist.district_id is null order by state_cd, county, city_county;




/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/
/**************************************************/

