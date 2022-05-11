use adagreatlakes13_db;
SELECT id, name, (category.group), SUBSTRING(rtrim(name), 1, length(rtrim(name))-5) as state FROM adagreatlakes12_db.category where (category.group=2) order by (category.group), name;

SELECT resource, id, SUBSTRING(rtrim(name), 1, length(rtrim(name))-5) as state FROM adagreatlakes12_db.resource1_v where status<>0 and ltrim(resource)<>'' and (resource1_v.group=2) order by status, ltrim(resource) asc;


Create View `resource_import_v` as 
SELECT id, status, createdBy, lastUpdatedBy, creationDate, lastUpdate, alias, ltrim(name) as name, website, address, voice, tty, fax, email, otherPhone, description, notes 
FROM adagreatlakes13_db.resource_import where status<>0 and ltrim(name) != "" order by ltrim(name);

/* the field: [name] in this record (in our import data table) begins with a quotation mark, that should be removed */
select * from resource_import where id = 300;

/* load imported data into our main table */
use adagreatlakes13_db;
truncate table resource;
insert into resource(id, status, createdBy, lastUpdatedBy, creationDate, lastUpdate, alias, name, website, address, voice, tty, fax, email, otherPhone, description, notes)
select id, status, createdBy, lastUpdatedBy, creationDate, lastUpdate, alias, name, website, address, voice, tty, fax, email, otherPhone, description, notes from resource_import_v;


CREATE VIEW `resource1_v` AS SELECT Category.group, Resource.id as res_id, Resource.name AS resource, Resource.alias, Category.id AS category_id, Category.name AS category, Resource.website, Resource.address, Resource.voice, Resource.tty, Resource.fax, Resource.email, Resource.otherPhone, Resource.description, Resource.notes, Resource.lastUpdate, Resource.lastUpdatedBy
FROM Category INNER JOIN (Resource INNER JOIN ResourceCategory ON Resource.id = ResourceCategory.Resource_id) ON Category.id = ResourceCategory.Category_id
ORDER BY Category.group, Resource.id, Category.name;


SELECT qResource.id, qResource.status, qResource.createdBy, qResource.lastUpdatedBy, qResource.creationDate, qResource.lastUpdate, qResource.alias, qResource.name, qResource.website, qResource.address, qResource.voice, qResource.tty, qResource.fax, qResource.email, qResource.otherPhone, qResource.description, qResource.notes, Category.name AS category
FROM Category INNER JOIN (resource_import_v as qResource INNER JOIN ResourceCategory ON qResource.id = ResourceCategory.Resource_id) ON Category.id = ResourceCategory.Category_id where status<>0;


SELECT resource, alias, website, address, voice, tty, fax, email, otherPhone, description, notes, lastUpdate, lastUpdatedBy, count(1) AS numRecs
FROM resource1_v
GROUP BY resource, alias, website, address, voice, tty, fax, email, otherPhone, description, notes, lastUpdate, lastUpdatedBy;


SELECT resource, alias, website, address, voice, tty, fax, email, otherPhone, description, notes, lastUpdate, lastUpdatedBy
FROM resource1_v
where category ="Federal Agencies";




Create View `resource1_totals_v` as 
select res_id, resource, alias, website, address, voice, tty, fax, email, otherPhone, description, notes, lastUpdate, lastUpdatedBy, sum(if(resource.group=1, 1, 0)) as NationalRecs, sum(if(resource.group=2, 1, 0)) as StateRecs, sum(if(resource.group=3, 1, 0)) as Specialties
FROM adagreatlakes13_db.resource1_v as resource group by res_id, resource, alias, website, address, voice, tty, fax, email, otherPhone, description, notes, lastUpdate, lastUpdatedBy;

SELECT * FROM adagreatlakes13_db.resource1_totals_v order by nationalrecs desc;

SELECT * FROM adagreatlakes13_db.resource1_totals_v where specialties>2;

SELECT * FROM adagreatlakes13_db.resource1_totals_v where staterecs=1;

SELECT * FROM adagreatlakes13_db.resource1_totals_v where nationalrecs=2;

/*
Add fields:
specialties
staterecs
nationalrecs
specialty1
specialty2
specialty3
nationalorg1
nationalorg2
state
*/

use adagreatlakes13_db;
  update resource res
     join resource1_totals_v tot on res.id = tot.res_id
     set res.staterecs = tot.staterecs,
	res.nationalrecs = tot.nationalrecs,
	res.specialties = tot.specialties,
	res.nationalorg1 = "",
    res.nationalorg2 = "",
    res.specialty1 = "",
    res.specialty2 = "",
    res.specialty3 = "",
    res.state = "";
    
    
    update resource res
     join resource1_totals_v tot on res.id = tot.res_id
     set res.state = "";
    
    
    SELECT resource, res_id, category FROM adagreatlakes13_db.resource1_v as res where res.group=1 order by res_id, category;
    
   SELECT id, name, nationalrecs FROM adagreatlakes13_db.resource where nationalrecs>0 order by id;
   
   
SELECT resource, id, category FROM adagreatlakes12_db.resource1_v as res where res_id=1 and res.group=1 order by category;

SELECT resource, res_id, category FROM adagreatlakes13_db.resource1_v as res where res_id=1 and res.group=1 order by category;

SELECT resource, res_id, category FROM adagreatlakes13_db.resource1_v as res where res_id=1 and res.group=1 order by category;


SELECT resource, res_id, category FROM adagreatlakes13_db.resource1_v as res where res_id=345 and res.group=1 order by category;
SELECT resource, res_id, category FROM adagreatlakes13_db.resource1_v as res where res_id=522 and res.group=1 order by category;

SELECT id, name, nationalrecs FROM adagreatlakes13_db.resource where nationalrecs>0 order by id;

use adagreatlakes85_db;

CREATE TABLE `wp_webinar_session` (
  `id` int(10) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `status` int(1) unsigned NOT NULL,
  `fiscalYear` smallint(4) unsigned NOT NULL,
  `activated` bit(1) DEFAULT b'0',
  `category_id` int(11) DEFAULT NULL,
  `Questions` bit(1) DEFAULT b'0',
  `webinarURL` varchar(255) DEFAULT NULL,
  `webinarPasscode` varchar(255) DEFAULT NULL,
  `WebinarID` varchar(45) DEFAULT NULL,
  `teleconferenceNumber` varchar(75) DEFAULT NULL,
  `teleconferencePasscode` varchar(75) DEFAULT NULL,
  `captioningURL` varchar(255) DEFAULT NULL,
  `streamingAudioURL` varchar(255) DEFAULT NULL,
  `webinarCapacity` int(11) DEFAULT NULL,
  `telephoneCapacity` int(11) DEFAULT NULL,
  `BoxURL` varchar(255) DEFAULT NULL,
  `publishTranscript` tinyint(1) NOT NULL,
  `archivelink` varchar(255) DEFAULT NULL,
  `audioTranscript` varchar(255) DEFAULT NULL,
  `VideoTranscript` varchar(255) DEFAULT NULL,
  `writtenTranscript` varchar(255) DEFAULT NULL,
  `rawTranscript` longtext,
  `unitPrice` decimal(5,2) unsigned NOT NULL,
  `SurveyGizmo` varchar(200) DEFAULT NULL,
  `SurveyMonkey` varchar(200) DEFAULT NULL,
  `cequizlink` varchar(255) DEFAULT NULL,
  `courseNumber` varchar(55) DEFAULT NULL,
  `ICCNumber` varchar(55) DEFAULT NULL,
  `LACESNumber` varchar(55) DEFAULT NULL,
  `notes` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



/* Error Code: 1175. You are using safe update mode and you tried to update a table without a WHERE that uses a KEY column.  To disable safe mode, toggle the option in Preferences -> SQL Editor and reconnect.	0.000 sec */



/* Various select statements written while writing code to copy data from Accessibility Online*/
use adagreatlakes86_db;

SELECT id, sesDate, sesTime, duration, sessionName, description, app, app_id, URL, fiscalYear, captioningURL, webinarURL, passcode, boxURL, videoTranscript, survey, startDate, endDate, activated FROM adagreatlakes90_db.wp_webinar_session where (startDate > NOW()-1 and startDate < NOW() + INTERVAL 90 DAY) order by startDate;

SELECT id, sesDate, sesTime, duration, sessionName, description, app, app_id, URL, fiscalYear, captioningURL, webinarURL, passcode, boxURL, videoTranscript, survey, startDate, endDate, activated FROM adagreatlakes90_db.wp_webinar_session where (startDate > NOW() and startDate < NOW() + INTERVAL 90 DAY) order by startDate;

/*

	$sqlStatement = "SELECT ses.id, date(startDate) as sesDate, time(startDate) as sesTime, TIMEDIFF(endDate, startDate) as duration, prod.name sessionName, prod.description as descrip, concat('https://accessibilityonline.org/ada-tech/session/?id=', ses.id) as URL, app.name as app, aprod.application_id as app_id, fiscalYear, activated, webinarURL, webinarpasscode as passcode, captioningURL, boxURL, videoTranscript, SurveyGizmo as survey, startDate, endDate
	FROM access20_accessboard.session as ses join access20_accessboard.applicationproduct aprod on ses.id=aprod.Product_id 
										left join access20_accessboard.application app on aprod.application_id=app.id 
											 join product prod on ses.id = prod.id where startDate > NOW() - INTERVAL 120 DAY order by startDate;";
	




*/



Select * from adagreatlakes86_db.wp_posts WHERE post_type='training_calendar';


SELECT app, app_id, count(*) as numRecs FROM adagreatlakes86_db.wp_webinar_session group by app, app_id order by app_id;

Select * from adagreatlakes86_db.wp_posts WHERE post_type='training_calendar';
/* 52 row(s) returned */
Select * from adagreatlakes86_db.wp_posts;
/* 1308 row(s) returned */

DELETE FROM adagreatlakes86_db.wp_posts WHERE post_type='training_calendar';

Delete FROM adagreatlakes86_db.wp_webinar_session where sesDate > date('2022/01/01');

DELETE FROM adagreatlakes86_db.wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts);
Select * FROM adagreatlakes86_db.wp_postmeta;
/*21096 row(s) returned*/

DELETE FROM adagreatlakes86_db.wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts);
Select * FROM adagreatlakes86_db.wp_term_relationships;
/*594 row(s) returned*/



Select * FROM wp_postmeta WHERE post_id IN ('110937', '110955');
Select * FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts);


/*
    $wpdb->query("DELETE FROM wp_posts WHERE post_type='training_calendar'");
	$wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts);");
	$wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)");
*/






