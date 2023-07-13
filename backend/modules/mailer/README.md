Mailer - solution for emailing users.
========================================================

SimpleMailer is a module that helps the administrator on creating and delivering emails from your application. 


What is this contained in this module:
======================================

A Template system:
------------------
The template system allows you to create multiple email templates that are going to be sent from your application.

Mail Queue:
-----------
With SimpleMailer you can always send your emails just after building them or enqueue them for later delivery.

Kind-of-Mailing-List creator:
-----------------------------
A very simplistic Mailing list creator. Basically it allows you to filter your database to select the desired users by using a SQL query.

Installation instructions
=========================

- copy module folder and past it into the backend/modules directory.

- Execute the following commands:

```bash
cd /your/app/directory/
yii migrate/up mailer  --migrationPath=@backend/modules/mailer/migrations
```

- Run 'crontab -e' and add the Mailer command:

```crontab
0,30 * * * * /path/to/your/application/yii mailer/cron #Send emails in queue every 30 minutes
```

- In your backend/config/main.php and config.console.php add the following lines:

```php
    'components' => [
        'mailer' => [
            'class' => 'backend\modules\mailer\components\Mailer'
        ],
        ....
    ]
    'modules' => [
        'mailer' =>  [
            'class' => '\backend\modules\mailer\Mailer',
            // This is the default value, for attaching the images used into the emails.
            'attachImages' => true,
            // Also the default value, how much emails should be sent when calling yiic mailer
            'sendEmailLimit'=> 500,
        ],
        ....
    ]
```
- Now access Mailer via http://your_app_ip_or_domain/mailer/. You're done.


Usage:
======

For sending mail to specific user with specific template

```php
	$template_vars = array(
			//Put any variables you need to replace. the suggested format is '__KEY__' => 'value'. More about this below.
			'__username__' => 'John Doe',
			'__quote__' => 'Roses are red, Violets are blue. Sugar is sweet, Who the hell are you?',
			);
    //If you want to enqueue the email for later sending just call Mailer::enqueue() instead. Same params, please.
    //For list email sending read below.
    Mailer::send('johndoe@example.com', 'template_name', $template_vars);
```
For sending list of mails 

```php
//Mailer::sendToList() enqueues all the messages being sent.
//The $template_vars are generated with the output of the SQL sentence.
Mailer::sendToList('list_name', 'template_name', $template_partials);
```
