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

# 13 

today we are going to do home and categories page dynamic

first uh Home page la iruka brands and categories ah dynamic ah mathuvom.. 

lets go to homepage component

$brands = Brand::where('is_active',1)->get();  //we are only getting active brands so that why is_active 1, it doesnt get the inactive brands

loop this in blade file 

also do that in categories 

...

next go to CategoriesPage do just like this .. 

# 14

today we are doing Products Page dynamic

before that mobile view la use pannum pothu hamburgder symbol ah click panna onnum aagala.. 
this becoz we used wire:navigate method

lets fix this 

document.addEventListener('livewire:navigated', () => {
window.HSStaticMethods.autoInit();
})  

paste this code in app.js .. now its working perfectly

now lets go to ProductsPage-> work that

after finished this , lets jump-in to ProductDetailPage

when we click the product that slug is working perfectly but that Details are in static , lets fix this 

all done for product detail page too

# 15 

today we are going to working on Product - filter (on the left side)

 what we do is if i selected any (checkbox) that things only i want to show

we finished all filters in left side of the Products .. then we working on some filters in home and categories 

everything works good 

# 16 

Today we are working on cart management system

create -> Helpers folder in App directory then create a CartManagement.php

we are going to write all the CartManagement Helper Functions here 

.. 

yeah we write all function in this helper class.. we can reuse this whenever we want , we are going to use these helper methods in next episode 


# 17 

previously we are creating Helper methods for CartManagement we are going us this

first go to ProductsPage workon Add to cart button and it functions using helper method

then working on Navbar that cart items count make dynamically 

then we are going to do sweet alert so lets go https://github.com/jantinnerezo/livewire-alert

follow the instructions 

> composer require jantinnerezo/livewire-alert

then use this cdn and script in layout file 
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <x-livewire-alert::scripts />

try the demo in that github page 

so , intha Sweet alert namaku epo kaatanum naa , cart la add pannathuku aprom so

so go to addToCart function.. 

    // add product to cart method
    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCart($product_id);

        $this->dispatch('update-cart-count', total_count : $total_count);

        $this->alert('success', 'Product added to the cart successfully..', [
            'position' => 'bottom-end',
            'timer' => 5000,
            'toast' => true,
        ]);
    }

    dont forget to use -> use LivewireAlert;

    then make sure to import this class

    now try to add cart its working perfectly.. 

    Next we are going to working on ProductDetailPage ->add cart

there we have - and + .. we write the function for this and make a wire click in blade file 

next we are working on ProductDetailPage Add to Cart Button

we can also did this addToCart function in ProductPage copy and paste it here too

now all working perfectly.. 

# 18 

today we are working on Cart Page

COMPLETED.. 

# 19 

today we are going to working on user authentication system

goto Register Page blade file , assign the name , email and password to wire:model and 
also form tag and give save function there

then come to class file
public $name;
public $email;
public $password;

    // Register User
    public function save(){
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|max:255',
        ]);

        // save to database
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        //Once User is store in database , we need to login

        // login user
        auth()->login($user);

        // redirect to the Home Page
        return redirect()->intended();

then go to blade file and update error message 

now try to signin it stores the user in database

after we login we should not see the login button in the navbar

Route la ithellam group panni authentication user ah iruntha mattum intha route lam show pannanum nu solrom

    Route::middleware('auth')->group(function () {
    Route::get('logout', function (){
    auth()->logout();
    return redirect('/');
    });
    
        Route::get('/checkout', CheckoutPage::class);
        Route::get('/my-orders', MyOrdersPage::class);
        Route::get('/my-orders/{order}', MyOrderDetailPage::class);
        Route::get('/success', SuccessPage::class);
        Route::get('/cancel', CancelPage::class);
    });

athey mari guest ku intha route laam show pannanum nu solrom

    Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class);
    Route::get('/register', RegisterPage::class);
    Route::get('/forgot', ForgotPasswordPage::class);
    Route::get('/reset', ResetPasswordPage::class);
    });

go to navbar.blade

cut dropdown menu into inside of this

@auth
@endauth

and then cut and paste the Login Section into 

@guest
@endguest

then change the user name dynamically using ->   {{ auth()->user()->name }}

then go to logout section  , go to href pass the logout route there

now try to logout , its working perfectly..

Now lets working on signin page 

goto login-page blade file 

give wire model to form tag , email, password 

then comeback to class file 


    public $email;
    public $password;

    public function save() {
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:6|max:255',
        ]);

        if(!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Invalid Credentials');
            return;
        }

        return redirect()->intended();

write this .. 

its working very well ... 

# 20

today we are working on forget password and reset password

goto ForgotPasswordPage

go to  its blade file give the wire:model and wire:submit for form and email 

then back to class file 


    public $email;

    public function save(){
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255'
        ]);

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if($status === Password::RESET_LINK_SENT){
            session()->flash('success', 'Password reset link has been sent to your email.');
            $this->email = '';
        }
    }

then back to blade file and write the error message for forgot password

.. this works fine 

now we want to send the mail for forgot password and reset it 

we want to set the mail in .env file [Mailer section]

first go to mail trap

# Looking to send emails in production? Check out our Email API/SMTP product!
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=80fe1fda46d9ab
MAIL_PASSWORD=cffeac8ade5e4d     copy this from mailtrap

paste it in mailer section in .env

change the route name like this ..     Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');

then enter the email now press reset password button.. check the mail trap there is a mail here 

but here is any confirmation message shown here , so lets fix it 

write the code for this is in blade file .. 


now lets go to mailtrap , open the mail Press the Reset Password button .. it will be redirect to the Reset Password Page

forgot password page things are finished , now lets move on to Reset Password Page

go to its blade file define wire:submit in form tag and wire:model in Password and Confirm Password

the go to class file 

    $public $token;

    #[Url]
    public $email;
    public $password;
    public $password_confirmation;

    public function mount($token){
        $this->token = $token;
    }

    public function save(){
        $this->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function (User $user, string $password) {
                $password = $this->password;
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET ? redirect('/login') : session()->flash('error', 'Something went wrong');
    }

write error message in blade file 

now try  to reset the password type Password and Confirm Password

Now try to login that changed password in login page , its successfully logged in

lets show the flash message 

write the code for that.. 

that's it .. 

# 21 today we are going to working on checkout page

before that , if we go to admin panel with other user , they can access it , its bad. admin should only can access the admin page

go to filament , Panel Builder -> Allowing users to access a panel

go to User Model 

    class User extends Authenticatable implements FilamentUser -> use this in class file 

and create this function

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email == 'admin@gmail.com';          -> i want this user only can access the admin panel 
    }

if i try access admin panel from different user , it shows 403 error

ok lets moveon to checkout page

        public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total
        ]);
    }

first we are going to working on Order Summary Section

we are completed Order Summary and Basket Summary 

now lets move on to shipping address

 lets go to class file 

        public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;

    public $zip_code;
    public $payment_method;

    public function placeOrder(){
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);
    }

according to this write the blade file. and error message .. 

next epi we are going to write for the PlaceOrder Functionality.. 

# 22

lets working on shipping address form

we will also see how to use stripe

goto dashboard.stripe webpage

enable -> test mode

Developers -> Api -> Secret Key (Copy that secret key)

open .env file -> paste the key in STRIPE_SECRET

STRIPE_SECRET = sk_test_51QeSM5HhP3h1h3njWBJ7UW6ih4EcFou8IrJgjx6ZGutpshuH1MFs4DjCxtESNI6ax5cDmGCxoi0RaAAdZoQYlWeZ00J342YBGk

stripe pw - Rajeshrj18k@

next we need to install stripe php sdk

search stripe sdk in google , you can see the github page 

> composer require stripe/stripe-php

Lets working on placeOrder Method 

complete the placeOrder method .. 

now goto Place Order in web , fill the form now try to pay with stripe (using dummy data)

it successfully redirect to the success page , then saw the url you can also get the session id
(we can modify this success page in next epi)

# 23

today we are going to see , how to send email to customer after order , and then make a success page dynamic

lets start with a mail first

go to laravel docs -> search mail

go for Markdown Mailables

> php artisan make:mail OrderShipped --markdown=mail.orders.shipped

change that to this

>$ php artisan make:mail OrderPlaced --markdown=mail.orders.placed

it will created two files one in app folder another one is in resources/views directory

go to class file 

assign this public property,
    public $order;

then go to construct method

    public function __construct($order)
    {
        $this->order = $order;
    }

then go to content method

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.orders.placed',
            with: [
                'url' => route('my-orders.show', $this->order),
            ]
        );
    }

pass the url like this .. we define this url name in routes

then go to its blade file , write this 

    <x-mail::message>
    # Order Placed successfully
    
        #Thank you for your order. Your Order number is: {{ $order->id  }}.
    
    <x-mail::button :url="'$url'">
    View Order
    </x-mail::button>
    
    Thanks,<br>
    {{ config('app.name') }}
    </x-mail::message>

go to CheckoutPage

        Mail::to(request()->user())->send(new OrderPlaced($order)); //we creating this OrderPlaced class in Mail Directory
add this before redirect to the url

make sure import that OrderPlaced .. 

now lets try to place order in web site , fill up fields and this time select cash on delivery , then click placeorder 

its working , go to mail trap and checked it that order confirmation is received or not .. its received .. 
if you click that view order in mail its redirect to the Order details page

now we are going to make success page dynamic 




