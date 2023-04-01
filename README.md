
![Banner](https://raw.githubusercontent.com/waadmawlood/laravel-duplicated-unique-soft-delete/main/banner/banner.png)


# 📌 Duplicated Unique Soft Delete Package

Deleting unique columns using soft delete is not a recommended method but works in some cases where the data is not important.

&nbsp;

## 🧔 Authors

- [Waad Mawlood](https://www.github.com/waadmawlood)
- waad_mawlood@outlook.com

&nbsp;

## 🎈 Mini Requirement

- PHP >= 8.0.0

&nbsp;

## 💯 Installation

Installation By Composer

```sh
composer require waad/laravel-duplicated-unique-soft-delete
```
&nbsp;

## 🧰 Usage

In **Model**
```js
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Waad\DUSD\DeleteUniqueable;

class Post extends Model
{
    use SoftDeletes;
    use DeleteUniqueable;


    // Important: Unique columns to check
    public $unique_attributes = ['serial_number', 'mac_address'];
    
```

&nbsp;

- We have to parameters to help your choice in request

 #### *1 - unique_or_operator*    
 It does not check all unique columns, it must be variable, but it takes the least consideration, which is at least one variable column, to delete the old one and enter the new one.

values = `1` or `true`

&nbsp;

#### *2 - unique_select_attributes*
It does not check all unique columns specified in Moodel but only those specified here in this parameter. 

eg. values = `serial_number,mac_address`

&nbsp;

⚠️ Note must remove unique validation from request form of controller

eg. 

```js
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'serial_number' => 'required|string|max:255,
        'mac_address' => 'required|string|max:255',
    ];
}
```

**Instead Of**

```js
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'serial_number' => 'required|string|max:255|unique:items,serial_number',
        'mac_address' => 'required|string|max:255|unique:items,mac_address',
    ];
}
```

&nbsp;

## 🎀 Example

- POST method created example
```js
name:Item 3
serial_number:s6
mac_address:m6
```
&nbsp;

use parameters
```js
name:Item 3
serial_number:s6
mac_address:m6
unique_select_attributes:serial_number
unique_or_operator:1
```

⚠️ when use `unique_or_operator` here will check only `serial_number` and use `or` in checking regardless `mac_address` if exist with `serial_number` in same record

&nbsp;

## 🎯 License

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
