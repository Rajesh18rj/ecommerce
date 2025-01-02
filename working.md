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

# 3 

In this Episode we are working on CategoryResource and BrandResource


php artisan make:filament-resource Customer --generate

    Automatically generating forms and tables (if you use --generate flag)

        If you'd like to save time, Filament can automatically generate the form and table for you, based on your model's database columns, using --generate:
ipo namma admin panel ah open panni, category ah pathom na , Namma table la kudutha ella field um automatic ah anga generate agi irukum.. 

but namma itha(form-ah) use nanna porathilla, itha modify panni than use panna porom.. 

Go-to CategoryResourse

 public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Grid::make()
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $operation, $state, Set $set ) => $operation ===
                                'create' ? $set('slug', Str::slug($state)) : null),
                                // Why we giving these last two line coz, if someone type name , it automatically generates slug

                            TextInput::make('slug')
                                ->maxLength(255)
                                ->disabled()
                                ->required()
                                ->dehydrated()
                                ->unique(Category::class, 'slug', ignoreRecord: true)

                        ]),

                        FileUpload::make('image')
                            ->image()
                            ->directory('categories'),

                        Toggle::make('is_active')
                            ->required()
                            ->default(true)


                    ])
            ]);
    }

    if image is not loading properly , 

    1) php artisan storage:link
    2) go to .env change app url local host to http://127.0.0.1:8000

    then we are going to our table section , we are already generate all the need table things using --generate method.. So we not making any changes in table Section .. 

    we are only making changes in Actions just like we did in User Resourse 

                ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                    ])
            ])

CategoryResource was finished .. [DON'T FORGET TO IMPORT THINGS]

Now we are going to create a BrandResourse

 > php artisan make:filament-resource Brand --generate

   goto form field , this form field is exactly same like a category form field , so copy and paste that form content here..

   same field table like Category.. 

   then copy the actions from Category and paste it.. 

# 4 

In this epi we are going to working on ProductResourse

 php artisan make:filament-resource Product

 goto form section , fill those fields 

 then go to Table section , fill that what fields we want .. 

 now goto filter section .. we want to filter by brand and category so 

                ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name'),

                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
            ])

 then asusual go to action section .. group them (edit, view , delete)  

# 5 

Today we are working on OrderResource

php artisan make:filament-resource Customer --view

generally it creates three files if we doesnt use view flag, if we use this tag we can get 4 files .. 

  if you open the OrderResource Folder , you can see the 4th File ViewOrder , we didnt work that file now , we work on that later..

Fill the Form what fields we want
 
 then asusual write actions 

 finally if order is created , we want to indicate that , so 

     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null {
        return static::getModel()::count() > 10  ? 'success' : 'danger';
    }
          
# 6

Intha epi la enna panna porom naa, address kum Order kum Relationship create panna porom.. 

Already Order Model la pathom naa.. address ku function yeluthi irukom...

go to filament -> Managing Relationships 

     php artisan make:filament-relation-manager OrderResource address street_address

go to OrderResource -> RelationManager -> AddressRelationManager

    first go to -> OrderResource 

     public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }

    now save and go to browser , you can see the address column in below the edit order 

    then comeback to AddressRelationManager

    fill the Form and Table Field as usual .. 

    Note : This address only relationship with this Order , if u open another order you cant see this address there .. 

# 7 

today we are working Order Tabs (Group of Order Tabs)

go to ->filament -> Widgets

$ php artisan make:filament-widget Orderstatus --resource=OrderResource

goto ->OrderResource-> Widgets ->Orderstatus

    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
        ];
    }

now go to Orders Page There is no card shown there , to fix that (show that card)

    goto-> OrderResource->Pages->ListOrders     

write this .. 

        protected function getHeaderWidgets(): array {
        return [
            OrderStatus::class
        ];
    }

Now go to Order Now that Card is showing .. 

add this too in Orderstatus 

Stat::make('Order Processing', Order::query()->where('status', 'processing')->count()),

now we have 2 cards .. 

add new 2 cards 

Stat::make('Order Shipped', Order::query()->where('status', 'shipped')->count()),
Stat::make('Average Price', Number::currency(Order::query()->avg('grand_total'), 'INR'))

if we want to add these cards below the table 

protected function getFooterWidgets(): array {
        return [
            OrderStatus::class
        ];

order status is completed ..

lets move on to Tabs Group

goto -> ListOrders -> write this Tabs function

    public function getTabs(): array {
        return [
            null => Tab::make('All'),
            'new' => Tab::make()->query(fn($query) => $query->where('status', 'processing')),
            'shipped' => Tab::make()->query(fn($query) => $query->where('status', 'shipped')),
            'delivered' => Tab::make()->query(fn($query) => $query->where('status', 'delivered')),
            'cancelled' => Tab::make()->query(fn($query) => $query->where('status', 'cancelled')),
        ];
    }

# 8 

How to work on OrderRelationManager with user
 go to User Model there is no relation ship with Order make that one .. 

     public function orders(){
        return $this->hasMany(Order::class);
    }

now lets go to filament 
  goto-> managing Relationship

$ php artisan make:filament-relation-manager UserResource orders id
UserResource->RelationManager->OrdersRelationManager

ipo enaku user ah click panna (admin page la) athuku keelaiye antha user oda Orders show pannanum

athuku munnadi UserResource la poi .. relation ah sollanum 

        public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class
        ];
    }

Now Lets back to OrderRelationManager

we dont do form related things here , so remove the form content ..

then goto Tables section fill that.. 

then go to Action we dont need edit here , so remove it 

            ->actions([
                Action::make('View Order')
                    ->url(fn(Order $record):string => OrderResource::getUrl('view', ['record'=> $record]))
                    ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])

# 9

Today we are working on Dashboard Page

1) Display Latest latest order
2) Display Order Statistics
3) Implement Global Search on Admin Panel
4) Reorder the Navigation Items

goto ->filament ->panel builder -> dashboard ->on the right we can see table widgets

php artisan make:filament-widget LatestOrders --table

goto 
Filament (Folder)-> Widgets -> LatestOrders
lets do the table and action 

then we changing the default visit page 

goto -> Providers -> Filament -> AdminPanelProvider

go to widgets section, comment the old one then Update this Orderstatus class there

            ->widgets([
                Orderstatus::class,
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])


now are going to do global search in admin panel

goto filament -> Global Search

     protected static ?string $recordTitleAttribute = 'name'; 

     add this property in UserResource

now try search .. its shows the result of user name .. 

we can also do with BrandResource .. Paste that property in BrandResource .. then we search by Brand Name

if we want to search with multiple columns..    

    Globally searching across multiple columns
If you would like to search across multiple columns of your resource, you may override the getGloballySearchableAttributes() method. "Dot notation" allows you to search inside relationships:

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }  (add this in UserResource)

    now we are going to re-arrange the navigation items 

    goto -> filament -> Navigation

    Sorting Navigation Items 

    first go to UserResource 

    protected static ?int $navigationSort = 1; (first we want to show User so we give the value of 1)

    then do this in BrandResurce for 2 , Category 3 , Product 4 and OrderResource 5

    Now save and see the browser this navigations reordered .. 

# 10 

we are going to working on  user panel

install tailwind css in your project 

next we are going to preline.com

Framework Guides -> Laravel ->
> npm install preline // or yarn add preline
set all the thing seeing docs


then >npm run dev

then install livewire 

then we want layout file > php artisan livewire:layout

add the livewireScripts and Styles , @vite in app.blade.css  

then create livewire componenet

>php artisan make:livewire HomePage
(fill the blade and  write the route for this seeing working or not)

# 11

before we developing a Homepage we are going to create footer and navbar

first add home section codes .. hero, brand , categories , customer-review sections (using that GitHub code)

then create a livewire class 

> php artisan make:livewire CategoriesPage 

then paste the categories-page(Github code) code in blade file 

then going to create a route for categories
Route::get('/categories',CategoriesPage::class);

Now if you click Categories in Navbar its working .. it shows Categories 

then Lets Fix that Products Page 

> php artisan make:livewire ProductsPage

paste the products-page(Github code) in ProductsPage blade file

then go and create route for products 

Route::get('/products', ProductsPage::class);

Now reload and click the Products(in Navbar) its working.. 

Again we are going to build(Every we add a new component we should build)

Now its showing Beautifully.. 


Now lets move on to Carts 
  >php artisan make:livewire CartPage

then create the route for this.. 

and go to cart-page (Github) and paste that in blade file .. 

Now click the Cart , its working .. (do the build)


Next we are Working on ProductDetailPage 

> php artisan make:livewire ProductDetailPage 

make a route = Route::get('/products/{product}', ProductDetailPage::class);

and then paste the product-detail-page (Github code) in our blade file

click that its working .. 


Now we are going to design on checkout page 

>php artisan make:livewire CheckoutPage

make a route =

and then paste the checkout-page (Github) code in our blade file 

lets visit this route http://127.0.0.1:8001/checkout its working


Now we are going to design the MyOrdersPage

>php artisan make:livewire MyOrdersPage

and then paste the my-orders-page(Github) code in our blade file

make a route: Route::get('/my-orders', MyOrdersPage::class);

visit this route .. its working 


Next we are working on MyOrderDetailPage

and then paste order-detail-page.html code in our blade file 

make a route : Route::get('/my-orders/{order}', MyOrderDetailPage::class);

visit the route http://127.0.0.1:8001/my-orders/1 .. its working !





Now Lets move on to authentication Page Login / Register / Forget Password / Reset Password

> php artisan make:livewire auth.login-page
> php artisan make:livewire auth.register-page
>  php artisan make:livewire auth.forgot-password-page
>  php artisan make:livewire auth.reset-password-page

then paste the html code from Github for each blade file 

then make a route

Route::get('/login', LoginPage::class);
Route::get('/register', RegisterPage::class);
Route::get('/forgot', ForgotPasswordPage::class);
Route::get('/reset', ResetPasswordPage::class);

then check the login and etc .. all are working well 

two more pages are left success and cancel page 

lets do this

> php artisan make:livewire SuccessPage

> php artisan make:livewire CancelPage

then paste the success and Cancel page codes from Github to this blade file 

then make the routes

Route::get('/success', SuccessPage::class);

Route::get('/cancel', CancelPage::class);

then check these pages .. in website it works perfectly

# 12

today we are working on active link and wire navigate of livewire 3

active link 

    ipo namma home paga la irukom naa.. home page mattum highlight pannanum apdi nu nenacha 

    {{ request()->is('/') ? 'text-blue-600' : 'text-gray-500' }}

    similarly for categories 

    {{ request()->is('categories') ? 'text-blue-600' : 'text-gray-500' }}

similary do with products and cart

wire navigation 

    ipo naan oru page la irunthu innoru page poren naa , whole page um reload aagitu than next page pogum , it takes some time.
itha avoid panna than wire navigation ah use panna porom

so go to -> navbar.blade.php

add this wire:navigate  in all inside the the anchor tag.. now try to click the home or categories or product .. its working without re-loading 

(actually its reloading in backend livewire did this in backend , so this isnt reloading )

do this in login and register page tooo.. (in anchor tag)








