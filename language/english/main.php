<?php
xoops_loadLanguage('main', 'tadtools');
define('_MD_ANCEN_SIGNUP_ID', 'number');
define('_MD_ANCEN_SIGNUP_ACTION_DATE', 'Activity Date');
define('_MD_ANCEN_SIGNUP_TITLE', 'Event Name');
define('_MD_ANCEN_SIGNUP_DETAIL', 'Event Description');
define('_MD_ANCEN_SIGNUP_END_DATE', 'Enrollment Deadline');
define('_MD_ANCEN_SIGNUP_END_DATE_FULL', 'Enrollment Deadline');
define('_MD_ANCEN_SIGNUP_STATUS', 'Enrollment Status');
define('_MD_ANCEN_SIGNUP_CANDIDATE_QUOTA', 'Number of available candidates');
define('_MD_ANCEN_SIGNUP_ACCEPT', 'Admissions');
define('_MD_ANCEN_SIGNUP_NOT_ACCEPT', 'Not Admitted');
define('_MD_ANCEN_SIGNUP_NOT_ACCEPT_NOT_YET', 'Not yet set');
define('_MD_ANCEN_SIGNUP_APPLY_DATE', 'Application date');
define('_MD_ANCEN_SIGNUP_IDENTITY', 'Identity');
define('_MD_ANCEN_SIGNUP_APPLY_LIST', 'Enrollment List');
define('_MD_ANCEN_SIGNUP_APPLY_NOW', 'Register Now');
define('_MD_ANCEN_SIGNUP_DELETE_SUCCESS', 'Successful deletion of event!');
define('_MD_ANCEN_SIGNUP_APPLY_SUCCESS', 'Successfully enrolled the event');
define('_MD_ANCEN_SIGNUP_APPLY_UPDATE_SUCCESS', 'Successful modification of registration data');
define('_MD_ANCEN_SIGNUP_APPLY_DELETE_SUCCESS', 'Successfully deleted enrollment data');
define('_MD_ANCEN_SIGNUP_ACCEPT_SUCCESS', 'Successfully set admission status');
define('_MD_ANCEN_SIGNUP_IMPORT_SUCCESS', 'Successful batch import of data');
define('_MD_ANCEN_SIGNUP_MY_RECORD', 'My enrollment record');
define('_MD_ANCEN_SIGNUP_SIGNIN_TABLE', 'Sign-in Table');
define('_MD_ANCEN_SIGNUP_SIGNIN', 'Signature');
define('_MD_ANCEN_SIGNUP_ACTION_SETTING', 'Event Settings');
define('_MD_ANCEN_SIGNUP_KEYIN', 'Please enter');
define('_MD_ANCEN_SIGNUP_NUMBER', 'Signup Number');
define('_MD_ANCEN_SIGNUP_NUMBER_OF_APPLY', 'Number of reported persons');
define('_MD_ANCEN_SIGNUP_CANDIDATE', 'Number of candidates');
define('_MD_ANCEN_SIGNUP_SETUP', 'Field Settings');
define('_MD_ANCEN_SIGNUP_ENABLE', 'Enabled or not');
define('_MD_ANCEN_SIGNUP_UPLOAD', 'Upload Attachment');
define('_MD_ANCEN_SIGNUP_ACTION_LIST', 'Activity List');
define('_MD_ANCEN_SIGNUP_IN_PROGRESS', 'Enrolling');
define('_MD_ANCEN_SIGNUP_UNABLE_TO_ENROLL', 'Unable to enroll');
define('_MD_ANCEN_SIGNUP_ADD_ACTION', 'Add');
define('_MD_ANCEN_SIGNUP_APPLIED_DATA', 'Enrolled Data');
define('_MD_ANCEN_SIGNUP_APPLIED_MAX', 'Maximum number of enrolments');
define('_MD_ANCEN_SIGNUP_NAME', 'Name');
define('_MD_ANCEN_SIGNUP_CHANGE_TO_NOT_ACCEPT', 'Change to Not Admitted');
define('_MD_ANCEN_SIGNUP_CHANGE_TO_ACCEPT', 'Change to admitted');
define('_MD_ANCEN_SIGNUP_PRODUCT_SIGNIN_SHEET', 'Generate sign-in table');
define('_MD_ANCEN_SIGNUP_EXPORT_OF_THE_APPLICATION_LIST', 'Export signup list');
define('_MD_ANCEN_SIGNUP_IMPORT_THE_APPLICATION_LIST', 'Import the enrollment list');
define('_MD_ANCEN_SIGNUP_IMPORT', 'Import');
define('_MD_ANCEN_SIGNUP_DOWNLOAD_CSV_IMPORT_FORMAT_FILE', 'Download CSV import format file');
define('_MD_ANCEN_SIGNUP_DOWNLOAD_EXCEL_IMPORT_FORMAT_FILE', 'Download Excel import format file');
define('_MD_ANCEN_SIGNUP_APPLY_FORM', 'Enrollment Form');
define('_MD_ANCEN_SIGNUP_EDIT_ACCTION', 'Edit Activity');
define('_MD_ANCEN_SIGNUP_ACCEPT_STATUS', 'Admission Status');
define('_MD_ANCEN_SIGNUP_NOT_YET_ANNOUNCED', 'Not yet announced');
define('_MD_ANCEN_SIGNUP_SIGNIN_TABLE_FIELD_SETTING', 'Sign to table field setting');
define('_MD_ANCEN_SIGNUP_DATA_PREVIEW', 'Enrollment Data Preview');
define('_MD_ANCEN_SIGNUP_CANCELLATION', 'Cancel Enrollment');
define('_MD_ANCEN_SIGNUP_MODIFY', 'Modify enrollment information');
// class\Ancen_signup_data.php
define('_MD_ANCEN_SIGNUP_CANNOT_BE_MODIFIED', 'Check no enrollment data, cannot modify');
define('_MD_ANCEN_SIGNUP_END', 'Enrollment is closed, no further enrollment or modification of enrollment is possible');
define('_MD_ANCEN_SIGNUP_CLOSED', 'The application is closed, no more applications or changes can be made');
define('_MD_ANCEN_SIGNUP_FULL', 'Enrollment is full, no further enrollment is possible');
define('_MD_ANCEN_SIGNUP_CANT_WATCH', 'No enrollment data, unable to view');
define('_MD_ANCEN_SIGNUP_NO_TITLE', 'No title');
define('_MD_ANCEN_SIGNUP_NO_CONTENT', 'No content');
define('_MD_ANCEN_SIGNUP_UNABLE_TO_SEND', 'No number, unable to send notification letter');
define('_MD_ANCEN_SIGNUP_DESTROY_TITLE', '[%s] Cancellation of enrollment notification');
define('_MD_ANCEN_SIGNUP_DESTROY_HEAD', '<p>You signed up for [%s] event at [%s] has been cancelled at %s by %s. </p>');
define('_MD_ANCEN_SIGNUP_DESTROY_FOOT', 'To re-enroll, please link to ');
define('_MD_ANCEN_SIGNUP_STORE_TITLE', '[%s] Enrollment Completion Notification');
define('_MD_ANCEN_SIGNUP_STORE_HEAD', '<p>Your registration for the [%s] event at [%s] was completed at %s by %s. </p>');
define('_MD_ANCEN_SIGNUP_FOOT', 'For full details, please link to ');
define('_MD_ANCEN_SIGNUP_UPDATE_TITLE', '[%s] Notification of modification of enrollment data');
define('_MD_ANCEN_SIGNUP_UPDATE_HEAD', '<p>Your registration for the [%s] event at [%s] has been modified at %s by %s as follows:</p>');
define('_MD_ANCEN_SIGNUP_ACCEPT_TITLE', '[%s] Enrollment Admission Status Notification');
define('_MD_ANCEN_SIGNUP_ACCEPT_HEAD1', '<p>Your application for the [%s] event in [%s] was reviewed for [<h2 style="color:blue">acceptance</h2>], and your application information is as follows:</p>');
define('_MD_ANCEN_SIGNUP_ACCEPT_HEAD0', '<p>Your application for the [%s}] event in [%s] was reviewed as [<span style="color:red;">not accepted</span>], and your application data is as follows:</p>');
define('_MD_ANCEN_SIGNUP_SENDING_FAILURE', 'Failure to send notification letter');
define('_MD_ANCEN_SIGNUP_UNABLE_TO_OPEN', 'Unable to open');
