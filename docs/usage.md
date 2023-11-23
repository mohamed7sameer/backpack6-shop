<div dir="rtl">




# الاستخدام

يوضح هذا الملف بتنسيق Markdown كيفية استخدام هذه الحزمة لبناء واجهة مستخدم لمتجر قابلة للتخصيص بشكل كبير مع واجهة سلة التسوق ووظيفة الدفع باستخدام [Backpack for Laravel](https://backpackforlaravel.com).

## المحتويات:

- [1. عرض الفئات والمنتجات](./usage.md#1-showing-categories-and-products)
- [2. استخدام سلة التسوق](./usage.md#2-using-the-shopping-cart)
- [3. عملية الدفع والسداد](./usage.md#3-checkout-and-payment)
- [4. تكوين إضافي](./usage.md#4-additional-configuration)

&nbsp;

---

## 1. عرض الفئات والمنتجات

روابط سريعة:

- [1.1 الفئات](./usage.md#11-categories)
- [1.2 المنتجات](./usage.md#12-products)
- [1.3 خصائص المنتج](./usage.md#13-product-properties)
- [1.4 تغييرات المنتج](./usage.md#14-product-variations)

&nbsp;

### 1.1 الفئات

ما عليك سوى استخدام نموذج `mohamed7sameer\BackpackShop\Models\ProductCategory` كما تفعل مع أي نموذج CRUD آخر. على سبيل المثال:


```php
$categories = ProductCategory::has('products')->orderBy('name')->get();
```

وفي القالب الخاص بك:

```injectablephp
<ul class="category-menu">
    @foreach($categories as $category)
        <li><a href="/shop/{{ $category->slug }}/">{{ $category->name }}</a></li>
    @endforeach
<ul>
```
راجع جدول `product_categories` أو CRUD للحصول على أسماء الحقول المتاحة هنا.

&nbsp;

### 1.2 المنتجات

احصل على جميع المنتجات في فئة معينة باستخدام `$category->products` أو استخدم نموذج `mohamed7sameer\BackpackShop\Models\Product` كأي نموذج CRUD آخر. على سبيل المثال:





```php
// Category products
$category = ProductCategory::whereSlug('slug')->firstOrFail();
$products = $category->products;

// From model
$products = Product::orderBy('price', 'asc')->get();
```

وفي القالب الخاص بك:

```html
@forelse($category->products as $product)
    <div class="category-product" data-url="/shop/{{ $category->slug }}/{{ $product->slug }}">
        <div class="category-product-image">
            <img class="product-image" src="{{ $product->cover }}" alt="{{ $product->name }}">
        </div>
        <div class="category-product-info">
            <h3>{{ $product->name }}</h3>
        </div>
    </div>
@empty
    <div class="category-products-empty">
        No products in this category yet!
    </div>
@endforelse
```

انظر إلى جدول `products` أو CRUD للحصول على أسماء الحقول المتاحة هنا، أو أضف حقول إضافية باستخدام ملف التكوين، أو خصائص إضافية باستخدام CRUD لخصائص المنتج (انظر 1.3 أدناه).

&nbsp;

### 1.3 خصائص المنتج

هذا أمر سهل أيضًا. انتقل إلى لوحة التحكم لـ `خصائص المنتج` وأضف بعضًا. ثم قم بتحرير أو إضافة منتج على لوحة تحكم `المنتجات`، المتاحة في نهاية علامة التبويب `المعلومات`.

عرضها على الواجهة الأمامية باستخدام:





```html
@foreach($product->properties as $property)
    <span class="property-label">{{ $property->title}}: </span>
    <span class="property-value">{{ $property->value }}</span>
@endforeach
```



هذا هو!

&nbsp;

### 1.4 تفاصيل المنتجات

بشكل افتراضي، يوفر هذا الحزمة الخيار لاستخدام تفاصيل المنتج بدلاً من ذلك. تلك هي إصدارات للمنتج، يمكنك استخدامها لطلب نوع معين، ولكنك لا تريد إضافتها كمنتجات منفصلة.

يمكن الوصول إلى التفاصيل من خلال خاصية `$product->variations`، ويمكن أن تكون لديها غلاف وسعر مختلفين (لكن هذا هو كل شيء). فكر، على سبيل المثال، في بيع سجل فينيل بلونين، وجعل نسخة اللون أغلى قليلاً. هذا النوع من الأمور. انظر إلى استخدام عربة التسوق للتعامل مع كل ذلك.

قد تفضل فقط استخدام منتجات فردية، ولكن هذا هو هنا، أيضًا، إذا كنت بحاجة إليه.

&nbsp;

---

## 2. استخدام عربة التسوق

التنقل السريع:

- [2.1 API: إضافة منتج](./usage.md#21-api-add-product)
- [2.2 API: تحديث المنتج](./usage.md#22-api-update-product)
- [2.3 API: إزالة المنتج](./usage.md#23-api-remove-product)
- [2.4 عرض المحتويات](./usage.md#24-display-shopping-cart-contents)

&nbsp;

### 2.1 API: إضافة منتج

> باستخدام وحدة تحكم AJAX

توفر هذه الحزمة تلقائيًا مسار يمكنك استخدامه لإضافة منتج إلى عربة التسوق باستخدام AJAX. ما عليك سوى استدعاء `/shopping-cart/add-product` بالمعلمات التالية:








```javascript
data = {
    "product_id": "", // required, id must exist in products table,
    "quantity": "", // required, must be integer,
    "variation_id": "" // required only if using product variations
}
```

ستعيد مكالمة ajax ما يلي:

```javascript
result = {
    "success": true,
    "message": "", // success message, can be customized in the cart.php lang file,
    "product_count": 1, // number of products in the cart now,
}
```


يتم توفير متغير `product_count` حتى تتمكن من تحديث عداد المنتجات في عربة التسوق إذا كنت تستخدم ذلك في أي مكان في قوالبك.

> باستخدام مساعد shoppingcart()

إذا كنت بحاجة إلى القيام بكل ذلك بنفسك لأي سبب من الأسباب، يمكنك استخدام وظيفة `shoppingcart()->addItem(Product $product, int $quantity = 1, array $variation = [])`.

- `$product` يجب أن يكون مثيلًا لـ `mohamed7sameer\BackpackShop\Models\Product`
- `$quantity` يجب أن يكون عددًا صحيحًا إيجابيًا
- `$variation` يجب أن يكون فارغًا أو مصفوفة تحتوي على الأقل على خاصية `'id'` للتفاصيل التي تريد إضافتها

&nbsp;

### 2.2 API: تحديث المنتج

> باستخدام وحدة التحكم AJAX

توفر هذه الحزمة تلقائيًا مسار يمكنك استخدامه لتحديث عربة التسوق باستخدام AJAX. ما عليك سوى استدعاء `/shopping-cart/update-product` بالمعلمات التالية:




```javascript
data = {
    "product_id": "", // required, id must exist in products table and cart
    "quantity": 1, // required, must be integer,
    "variation_id": "", // required if using product variations
}
```

ستعيد مكالمة ajax ما يلي:

```javascript
result = {
    "success": true, // false if product id or variation was not in the cart
    "message": "", // success message, can be customized in the cart.php lang file,
    "product_count": 1, // number of products in the cart now,
}
```



يتم توفير المتغير `product_count` مرة أخرى حتى تتمكن من تحديث عداد المنتجات في عربة التسوق إذا كنت تستخدم ذلك في أي مكان في قوالبك.

> باستخدام مساعد shoppingcart()

إذا كنت بحاجة إلى القيام بكل ذلك بنفسك لأي سبب من الأسباب، يمكنك استخدام وظيفة `shoppingcart()->updateQuantity(Product $product, int $quantity = 1, array $variation = [])`.

- `$product` يجب أن يكون مثيلًا لـ `mohamed7sameer\BackpackShop\Models\Product`
- `$quantity` يجب أن يكون عددًا صحيحًا إيجابيًا
- `$variation` يجب أن يكون فارغًا أو مصفوفة تحتوي على الأقل على خاصية `'id'` للتفاصيل التي تريد تحديثها

&nbsp;

### 2.3 API: إزالة المنتج

> باستخدام وحدة التحكم AJAX

توفر هذه الحزمة تلقائيًا مسار يمكنك استخدامه لتحديث عربة التسوق باستخدام AJAX. ما عليك سوى استدعاء `/shopping-cart/remove-product` بالمعلمات التالية:










```javascript
data = {
    "product_id": "", // required, id must exist in products table and cart
    "variation_id": "", // required if using product variations
}
```

ستعيد مكالمة ajax ما يلي:

```javascript
result = {
    "success": true, // false if product id or variation was not in the cart
    "message": "", // success message, can be customized in the cart.php lang file,
    "product_count": 0, // number of products in the cart now,
}
```



يتم توفير المتغير `product_count` مرة أخرى حتى تتمكن من تحديث عداد المنتجات في عربة التسوق إذا كنت تستخدم ذلك في أي مكان في قوالبك.

> باستخدام مساعد shoppingcart()

إذا كنت بحاجة إلى القيام بكل ذلك بنفسك لأي سبب من الأسباب، يمكنك استخدام وظيفة `shoppingcart()->removeItem(Product $product, array $variation = [])`.

- `$product` يجب أن يكون مثيلًا لـ `mohamed7sameer\BackpackShop\Models\Product`
- `$variation` يجب أن يكون فارغًا أو مصفوفة تحتوي على الأقل على خاصية `'id'` للتفاصيل التي تريد إزالتها

&nbsp;

### 2.4 عرض محتويات عربة التسوق

يوفر مساعد `shoppingcart()` بعض الأدوات لعرض محتويات العربة التسوق.

&nbsp;

#### عدد المنتجات

يمكن استخدام المتغير `shoppingcart()->product_count` لعرض عدد المنتجات التي توجد حاليًا في عربة التسوق.

&nbsp;

#### المنتجات

من خلال `shoppingcart()->products`، يمكنك عرض المنتجات في العربة:










```html
@php($currencySign = config('mohamed7sameer.backpack-shop.currency.sign'))
@forelse(shoppingcart()->products as $product)
    <div class="cart-product">
        <div class="cart-product-img">
            <img src="{{ $product->cover }}">
        </div>
        <div class="cart-product-info">
            <div class="product-qty-name">
                {{ $product->quantity }} x <strong>{{ $product->name }}</strong>
            </div>
            <div class="cart-product-price">
                {{ $currencySign }} {{ number_format($product->price, 2, ',', '') }}
            </div>
            <div class="cart-product-total">
                {{ $currencySign }} {{ number_format($product->price * $product->quantity, 2, ',', '') }}
            </div>
        </div>
    </div>
@empty
    <div class="no-products">Oh no! The shopping cart is completely empty! <a href="/shop">Feed it!</a></div>
@endforelse
```


&nbsp;

#### الإجماليات

توفر العربة أيضًا وسيلة سهلة لعرض الإجماليات. يمكنك الحصول على مصفوفة تحتوي على جميع الإجماليات التي تحتاجها باستخدام `shoppingcart()->totals`، والتي ستقدم لك الاستجابة التالية:








```php
[
    'subtotal_incl_vat' => (float),
    'subtotal_excl_vat' => (float),
    'vat_subtotal' => (float),
    'shipping_incl_vat' => (float),
    'shipping_excl_vat' => (float),
    'shipping_vat' => (float),
    'shipping_description' => (string) // available only if shipping country has already been set; see below,
    'total_incl_vat' => (float),
    'total_excl_vat' => (float),
    'vat_total' => (float),
    'vat' => [
        [
            'description' => (string) // name of the VAT class taken from CRUD
            'vat' => (float) // amount of vat for this VAT class
        ]
        ...        
    ],
]
```



استخدم هذه في قالبك كما ترون مناسبًا.

&nbsp;

---

## 3. إتمام الشراء والدفع

التنقل السريع:

- [3.1 نموذج الشراء: العنوان والمعلومات](./usage.md#31-checkout-form-address-and-information)
- [3.2 الشحن والدفع](./usage.md#32-shipping-and-payment)
- [3.3 استخدام مقدمي الدفع المخصصين](./usage.md#33-using-custom-payment-providers)
- [3.4 معالجة الدفع وتأكيد الطلب](./usage.md#34-processing-payment-and-order-confirmation)

&nbsp;

### 3.1 نموذج الشراء: العنوان والمعلومات

هذه الحزمة موجهة للخلفية/CRUD، وهذا يعني أنها لا تشمل قوالب لواجهة المستخدم الأمامية لعملية الشراء. ومع ذلك، توفر وظائف تحكم لمعالجة المعلومات الضرورية لقبول الطلب وإنشاء طلب دفع.

بشكل افتراضي، يمكنك إرسال نموذج الشراء مع معلومات العنوان إلى `/shopping-cart/checkout` باستخدام طلب `POST`. يقوم هذا المسار بحفظ المعلومات في العربة ويعيد توجيه العميل إلى الخطوة التالية (حيث يجب عليك توفير عنوان URL، انظر أدناه).

بالإضافة إلى حقل `@csfr`، يجب أن يحتوي النموذج على الأقل على:

| الاسم         | النوع   | التعليق                                                                                         |
|--------------|--------|-------------------------------------------------------------------------------------------------|
| البريد الإلكتروني        | بريد إلكتروني  | يجب أن يكون عنوان بريد إلكتروني صالح                                                               |
| الاسم         | نص |                                                                                                 |
| العنوان      | نص |                                                                                                 |
| الرمز البريدي      | نص |                                                                                                 |
| المدينة         | نص |                                                                                                 |
| الدولة      | نص | من الأفضل استخدام اختيار يظهر فقط الدول التي تتوفر فيها قواعد الشحن الصحيحة<sup>1</sup> |
| عنوان URL للتوجيه | نص | حيث يتم إرسال العميل بعد التحقق من معلومات العنوان (انظر 3.2 أدناه)                    |

#### <sup>1</sup> عرض الدول ذات القواعد الصحيحة فقط:

تأتي هذه الحزمة مع وظيفة `bpshop_shipping_countries()` التي يمكنك استخدامها لملء حقل اختيار بفقط الدول التي تحتوي على قواعد شحن صحيحة. مثال كامل:












```html
<select class="select2" name="country" id="checkout-country">
    @php($current_country = old('country', shoppingcart()->getAddress('country')) ?: config('mohamed7sameer.backpack-shop.default_shipping_country'))
    @foreach(bpshop_shipping_countries() as $country)
        <option value="{{ $country }}" {{ $current_country === $country ? 'selected' : '' }}>{{ $country }}</option>
    @endforeach
</select>
```




&nbsp;

### 3.2 الشحن والدفع

يجب أن تعرض الشاشة التالية للعميل ملخصًا للطلب، والمعلومات المدخلة، وطريقة الشحن التي تم اختيارها لهم.

#### تفاصيل العنوان

عرض تفاصيل العنوان أمر سهل. يمكنك استخدام `shoppingcart()->getAddress('type')`، حيث يمكن أن يكون `type` أي متغير مدرج في الجدول أعلاه (باستثناء `redirect_url`).

#### تفاصيل الشحن

عرض تفاصيل الشحن أيضًا سهل. بشكل افتراضي، تختار هذه الحزمة خيار الشحن الأرخص المتاح للمنتجات في العربة والبلد المحدد. قد يكون من الممكن أن لا تكون هناك قواعد شحن صحيحة متاحة. في هذه الحالة، يجب عليك إيقاف المستخدم هنا وإخبارهم بما يجب فعله (على الرغم من أنه في الأفضل يجب أن تغطي قواعد الشحن التي تم إدخالها باستخدام لوحات التحكم كل السيناريوهات الممكنة).

استخدم مصفوفة `shoppingcart()->totals` للحصول على تكلفة الشحن بما في ذلك أو بدون ضريبة القيمة المضافة، وكذلك وصف الشحن. يمكنك أيضًا استخدام `shoppingcart()->shipping_incl_vat`، `shoppingcart()->shipping_excl_vat`، و `shoppingcart()->getShippingDescription()` مباشرة، ولكن استخدام مصفوفة الإجماليات ربما يكون أسهل.

#### طريقة الدفع

تتغير هذه القسم تبعًا لطريقة الدفع المستخدمة. راجع وثائق طريقة الدفع التي اخترتها لمعرفة ما إذا كان عليك فعل شيء آخر. ومع ذلك، عادةً، سيظل معظم هذا ساري المفعول.

بشكل افتراضي، يمكنك إرسال النموذج بوسيلة الدفع المحددة إلى `/shopping-cart/payment` باستخدام طلب `POST`. يقوم هذا المسار بحفظ المعلومات في العربة ويعيد توجيه العميل إلى عنوان URL الخروج الذي سيقدمه مزود الدفع.

بالإضافة إلى حقل `@csfr`، يجب أن يحتوي النموذج على الأقل على:

| الاسم           | النوع   | التعليق                                                               |
|----------------|--------|-----------------------------------------------------------------------|
| طريقة الدفع | معرف     | قيم صالحة تعتمد على طريقة الدفع التي تستخدمها<sup>1</sup> |

#### <sup>1</sup> عرض طرق الدفع الصالحة:

لعرض وسائل الدفع الصالحة لمزود الدفع المحدد في ملف `config/backpack-shop.php`، يمكنك استخدام وظيفة المساعدة `shoppingcart()->getPaymentMethods()`. مثال كامل:










```html
<select class="select2 payment-method" name="payment_method" id="payment-method">
    @foreach(shoppingcart()->getPaymentMethods() as $method)
        <option value="{{ $method['id'] }}" {{ old('payment_method', 'ideal') === $method['id'] ? 'selected' : '' }}>{{ $method['description'] }}</option>
    @endforeach
</select>
```




تلك الخطوات يجب أن تكون كافية لمعظم وسائل الدفع. عندما يتم إرسال هذا النموذج إلى `/shopping-cart/payment` باستخدام طلب `POST`، سيتم توجيه العميل إلى عنوان URL الخاص بالخروج الذي يتم توفيره من قبل مقدم الخدمة للدفع. بعد الدفع (نجاحًا أو فشل)، سيتم توجيه العميل مرة أخرى إلى الموقع. يمكنك تخصيص العرض الذي يتم عرضه لهم في هذه النقطة، في ملف `config/backpack-shop.php`. انظر أيضاً 3.4 أدناه.

افتراضيًا، إذا لم يتم تثبيت أي وسيلة دفع إضافية، يستخدم الحزمة مقدم الخدمة "NoPayment". وهذا يعني أنه بعد هذه الخطوة، بدلاً من توجيه العميل إلى مقدم الدفع، يتم عرض صفحة الشكر مباشرة، وترسل تأكيدات الطلب تلقائيًا أيضًا.

&nbsp;

### 3.3 استخدام مقدمي الدفع المخصصين

انظر إلى [payment-providers.md](./payment-providers.md)

&nbsp;

### 3.4 معالجة الدفع وتأكيد الطلب

عندما يعود العميل من مقدم الخدمة للدفع (أو على الفور، في حال استخدام مقدم الخدمة NoPayment الافتراضي)، يتم عرض العرض الذي تم تكوينه في ملف `config/backpack-shop.php`.

**ملاحظة: يجب ألا يتطلب العرض أي متغيرات موجودة، وإلا سيتم إثارة خطأ بسبب النقص في المتغيرات**.

المسار الافتراضي يوفر العرض بمتغير `$payment_result`، الذي يحتوي على:

- `$payment_result['status']` الذي يحمل حالة الطلب ('new', 'paid', 'cancelled', أو 'error')
- `$payment_result['msg']` لرسالة المترجمة المرافقة لتلك الحالة (انظر إلى فئة `Order` و `lang/*/order.php`)

في هذه النقطة، يكون الطلب متاحًا أيضًا في لوحة السيطرة `Orders` CRUD. هذا هو كل شيء، الطلب الآن إما معالج بالكامل أو ملغى. بشكل افتراضي، يتم تفريغ سلة التسوق عندما يكون الطلب ناجحًا (جديد أو مدفوع) ولكنه يتم الاحتفاظ بها عندما لا يتم معالجة الدفع بشكل صحيح (ملغى أو في حالة خطأ) حتى يتمكن العميل من المحاولة مرة أخرى دون فقدان العربة.

مقدم الدفع يحتوي على وظيفة تحدد متى يتم إرسال تأكيد الطلب، في هذه اللحظة سيتلقى العميل بريدًا إلكترونيًا يحتوي على فاتورة PDF المرفقة. مقدم الخدمة "NoPayment" الافتراضي يقوم بذلك على الفور عندما يتم وضع الطلب، حيث لا يوجد وسيلة لمعالجة الطلب بشكل إضافي. قد يقدم مقدمو الدفع الآخرون تكوينًا إضافيًا هنا (مرة أخرى، انظر إلى [payment-providers.md](./payment-providers.md)).

- يمكن تخصيص محتوى البريد الإلكتروني عن طريق تجاوز عرض `backpack-shop/views/email/invoice`.
- يمكن تخصيص محتوى PDF عن طريق تجاوز عرض `backpack-shop/views/pdf/invoice`.
- يمكن أيضًا تخصيص البريد الإلكتروني و PDF (جزئيًا) عن طريق نشر وتحرير ملفات اللغة.

&nbsp;

___

## 4 تكوين إضافي

هناك الكثير الذي يمكن تكوينه في هذه الحزمة. إذا وجدت الوقت في يوم من الأيام (أو يرجى أن تشعر بالحرية لمساعدتي، انظر إلى [contributing.md](../contributing.md))، سأحاول وضع كل شيء في مكان منطقي. ولكن هذا يعتبر الكثير من العمل لأشياء قد لا تكون مفيدة في معظم الحالات. لذلك، أقترح في الوقت الحالي أن تلقي نظرة على ملف `config/backpack-shop.php`، الذي يحتوي على معلومات إضافية وإشارات قد لا تكون مغطاة تمامًا في هذا الوثائق.









## ملحوظات





يشير ال price في التجارة الإلكترونية إلى السعر الذي يتم عرضه للمنتج على الموقع. 
بينما يشير ال sale price إلى السعر الذي يتم بيع المنتج به بعد تخفيضه. 
يتم استخدام ال sale price عادةً لزيادة المبيعات والترويج للمنتجات.
ومن الممكن أن يكون هناك تخفيضات مؤقتة في الأسعار لزيادة المبيعات،
وهذا ما يشير إليه ال sale price
أما بالنسبة ل price، فهو يشير إلى السعر الأصلي للمنتج قبل تخفيضه. 
وغالبًا ما يستخدم ال price في الترويج للمنتجات والإشارة إلى قيمة المنتج

<hr>

يشير “Product Properties” في التجارة الإلكترونية إلى الخصائص المختلفة للمنتجات، مثل الأبعاد والوزن والمواد المستخدمة في صنع المنتج. بينما يشير “Product Variations” إلى الإصدارات المختلفة من نفس المنتج، مثل الأحجام والألوان والأشكال. يمكن للعميل اختيار إحدى الإصدارات المختلفة لشراء المنتج.

عادةً ما يتم استخدام “Product Properties” لوصف المنتج بشكل أفضل، بينما يستخدم “Product Variations” لعرض الإصدارات المختلفة من نفس المنتج. يمكن أيضًا استخدام “Product Variations” لعرض تخفيضات مؤقتة في الأسعار.






</div>
