# Changelog

## v1.0.0 (2021-06-28)

* Initial release

## v1.1.0 (2021-07-27)

* Updated Nova Stubs (`Course` & `CourseBlock`)

## v1.2.0 (2021-07-28)

* Added ability to set `course_block_model` in the config
* Added foreign key to relationships in models
* Added scope `scopeWithProgress` query in `Course.php`
* Added `getShortReadableEstimatedLengthAttribute` in `Course.php`
* Added `getExcerptAttribute` in `Course.php` and `CourseBlock.php`
* Added `getFormattedEstimatedLengthAttribute` in `CourseBlock.php`
* Updated `getTimeLeftAttribute` in `CourseBlockProgress.php`

## v1.3.0 (2021-07-29)

* Added slug field to course blocks
* Removed nullable from title column in `create_course_blocks_table` migration

## v1.3.1 (2021-07-29)

* Fixed missing DB Facade import

## v1.3.2 (2021-07-29)

* Use course block estimated length as default for calculating user progress

## v1.3.3 (2021-07-29)

* Fixes error in `getReadableSizeAttribute` when video size column is null

## v1.3.4 (2021-07-29)

* Fixes variable typo in `ApiVideoDriver` class

## v1.3.5 (2021-08-04)

* Fixes progress percentage attribute in `CourseBlockProgress`