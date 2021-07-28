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
