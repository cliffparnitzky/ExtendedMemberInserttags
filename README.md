Contao Extended Member Inserttags Extension
===========================================

Provides additional insert tags for members.


Installation
------------

The extension can be installed using the Contao extension manager in the Contao
back end. If you prefer to install it manually, download the files here:

http://www.contao.org/en/extension-list/view/ExtendedMemberInserttags.html


Tracker
-------

https://github.com/cliffparnitzky/ExtendedMemberInserttags/issues


Insert tags
-----------

Provides all known insert tags to get information of the actual logged member (replace `user` with `member` !) of this [listing](http://contao.org/en/insert-tags.html#user-properties).

### Known insert tags are:

~~~~
{{member::firstname}} ... This tag will be replaced with the first name of the currently logged in user.
{{member::lastname}} ... This tag will be replaced with the last name of the currently logged in user.
{{member::company}} ... This tag will be replaced with the company name of the currently logged in user.
{{member::phone}} ... This tag will be replaced with the phone number of the currently logged in user.
{{member::mobile}} ... This tag will be replaced with the mobile number of the currently logged in user.
{{member::fax}} ... This tag will be replaced with the fax number of the currently logged in user.
{{member::email}} ... This tag will be replaced with the e-mail address of the currently logged in user.
{{member::website}} ... This tag will be replaced with the web address of the currently logged in user.
{{member::street}} ... This tag will be replaced with the street name of the currently logged in user.
{{member::postal}} ... This tag will be replaced with the postal code of the currently logged in user.
{{member::city}} ... This tag will be replaced with the city of the currently logged in user.
{{member::country}} ... This tag will be replaced with the country of the currently logged in user.
{{member::username}} ... This tag will be replaced with the username of the currently logged in user.
~~~~

### Also useful but not documented insert tags are:

~~~~
{{member::dateOfBirth}} ... This tag will be replaced with the date of birth of the currently logged in user.
{{member::gender}} ... This tag will be replaced with the gender of the currently logged in user.
{{member::state}} ... This tag will be replaced with the state of the currently logged in user.
{{member::language}} ... This tag will be replaced with the language of the currently logged in user.
~~~~

### Additional insert tags are:

~~~~
{{member::age}} ... This tag will be replaced with the age of the currently logged in user.
{{member::name}} ... This tag will be replaced with the name (combination of firstname and lastname) of the currently logged in user.
{{member::salutation}} ... This tag will be replaced with the salutation (`Mr.` or `Mrs.`) for the currently logged in user.
{{member::welcoming}} ... This tag will be replaced with the welcoming (`Dear Mr.` or `Dear Mrs.`) for the currently logged in user.
~~~~

### Improvements are

- For properties with regular expression of `date` / `time` / `datim` (defined in eval array of DCA config) a custom dateformat could be set (e.g. `{{member::dateOfBirth::d. M Y}}` will be replaced with `14. Nov 1991`). If no custom format was found, the systems default will be used.
- For properties of datatype `array` and existing foreign key (defined in DCA config) the text values will be read from database (e.g. `{{member::groups}}` will be replaced with `Piano Students, Violin Students`).

### Feature

* Each inserttag can be extended with the id of a special member to get the information about it. To use this feature add the id to the insert tags `{{member::ID::FIELDNAME}}`, e.g.:

~~~~
{{member::3::name}} ... This tag will be replaced with the name of the member with id `3`.
{{member::27:email}} ... This tag will be replaced with the e-mail address of the member with id `27`.
{{member::15:dateOfBirth::*}} ... This tag will be replaced with the date of birth of the member with id `15` (with custom format).
~~~~
