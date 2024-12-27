# 1

In this episode we do create Model and migration and set the relationship

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

# 2
In this episode we develop admin panel using filament
 
 install the filament and create the admin user using Docs .. 

We are not working on dashboard page today .. 

We are going to see how to work on user resources.. 

> php artisan make:filament-resource User

after go to admin panel you can see the user Option is created there..

we changed the user icon in UserResource.php 

now if i clicked new button in admin panel , there is no form there , lets create one .. (UserResource.php)

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required(),

                Forms\Components\TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->maxlength(255)
                ->unique(ignoreRecord: true)
                ->required(),

                Forms\Components\DateTimePicker::make('email_verified_at')
                ->label('Email Verified At')
                ->default(now()),

                Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn ($livewire): bool => $livewire instanceof CreateRecord),

            ]);
    }

    try to create a user .. it successfully created .. after created it redirects to the Edit user page .. 

    Now lets design the Table 

        public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),

                Tables\Columns\TextColumn::make('email')
                ->searchable(),

                Tables\Columns\TextColumn::make('email_verified_at')
                ->dateTime()
                ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()

            ])

            we added the columns , now we are moving to actions 

            there is already edit action is there , but i want delete and View action so 

                        ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

      we also add these into single group ..

                  ->actions([
                    Tables\Actions\ActionGroup::make([
                        Tables\Actions\ViewAction::make(),
                        Tables\Actions\EditAction::make(),
                        Tables\Actions\DeleteAction::make(),
                     ])

            ])   like this .. 

            if i clicked that three doted .. there is edit , delete and view           


