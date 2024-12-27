# 1

  ** Casts **

  In Laravel, this magic is done with $casts. You use $casts to tell Laravel how to treat things in your database.

Example:
Let’s say you have this:

"Age": You want it to always act like a number.
"Birthday": You want it to act like a date.
"Active": You want it to always be true or false, even if the database uses 1 and 0.
"Settings": You want it to turn JSON into a list you can easily use.
Here’s how you tell Laravel to do that:

php
Copy code
protected $casts = [
    'age' => 'integer',       // Always treat 'age' like a number
    'birthday' => 'datetime', // Always treat 'birthday' like a date
    'active' => 'boolean',    // Always treat 'active' like true/false
    'settings' => 'array',    // Turn JSON into a list
];
Now Laravel will:

Read your database: Automatically change things like 1 to true, or turn a date string into something you can use as a calendar date.
Write to your database: Change true back to 1, or turn your list into JSON.
Why is this helpful?
It saves you from having to do extra work. Instead of saying:

php
Copy code
$age = (int) $user->age;  // Change age to a number every time you use it
You just use $user->age directly because Laravel already knows it’s a number.

Think of $casts like a translator for your database:
It translates numbers, dates, true/false values, or even lists into what your app understands.
When you save stuff back, it translates things back into the language your database understands.
