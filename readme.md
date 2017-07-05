#Ribase Forms

##The death of Formhandler
At this point we got a big problem because Formhandler was not fully usable and with TYPO3 7.6.5 it was impossible to use. (They changed the flexform handling)
So at this point we got two options left, settle to powermail or build something own.

##Building this extension
At this point of "we have to decide now" i started to develop a simple formextension with fluid. The first release is more or less nothing where you can customize your inputs, they came directly from ready build up fluidtemplates.
But as time goes on a while there were more needs to cover up with this extension so the decision was to implement a backend to build up custom forms.

In the next section you will get a kickstart to use it in a simple way.

#Quickstart

##Composer install

```bash
$ composer require ribase/ribaseforms:2.* && composer update
```
Please note that minimum Version should be 2.*

##Manual install
No Support!

##Activate in TYPO3
####With TYPO3 console
```bash
$ cd to/your/webroot ; ./typo3cms extension:activate ribase_forms
```
####Without TYPO3 console
```bash
$ cd to/your/webroot ; ./typo3/cli_dispatch.phpsh extbase extension:install ribase_forms
```
####In Extension Manager
No Support!

##Plugin usage
1. Add new CE (content element)
2. Go to Plugins
3. Choose "Ribase Form Display"

###Create input elements
####Input Textfield
As you can see below there is an Input Textfield

![alt text](https://raw.githubusercontent.com/ribase/RibaseForms/master/docs/img/textfield.png "Textfield")

| Field | Description | 
| --- | --- | 
| Name | The name is used for an identification of this field later in mailtext [placeholders](#placeholders). | 
| Label | A label for the frontend display | 
| Placeholder | Sets a placeholder to the field | 
| Inputtype | In some cases you need something else than a "text", maybe for a telephone input you can use a tel field or for an email field you can use an email type. | 
| Required | Makes the field required | 

####Input Textarea
As you can see below there is an Input Textarea.

![alt text](https://raw.githubusercontent.com/ribase/RibaseForms/master/docs/img/textarea.png "Textarea")

| Field | Description | 
| --- | --- | 
| Name | The name is used for an identification of this field later in mailtext [placeholders](#placeholders). | 
| Label | A label for the frontend display | 
| Placeholder | Sets a placeholder to the field | 
| Required | Makes the field required | 

####Radiobutton
As you can see below there is an Input Textfield

![alt text](https://raw.githubusercontent.com/ribase/RibaseForms/master/docs/img/radiobutton.png "Radiobutton")

| Field | Description | 
| --- | --- | 
| Name | With radiobuttons we got a little special behaviour. In first the name is used for grouping the radiobuttons. The other usage is - if you read carefully - for placeholders in mailtexts later. [placeholders](#placeholders). | 
| Label | A label for the frontend display | 
| Required | Makes the field required | 

#Advanced usage
@todo

#Placeholder usage in replymails
##How do they work?

#Credits
[Sebastian Thadewald](https://github.com/ribase)

#Issues?

[Click here to drop an issue!](https://github.com/ribase/RibaseForms/issues)

#Donate

If you like it, donate! But not to me, donate it TYPO3, without extension wont exist!

[Donate TYPO3](https://typo3.org/donate/online-donation/)


