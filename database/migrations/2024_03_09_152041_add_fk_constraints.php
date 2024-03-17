<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('customer_contract_id')->references('id')->on('customer_contracts');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('employee_profile_id')->references('id')->on('employee_profiles');
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->foreign('employee_profile_id')->references('id')->on('employee_profiles');
            $table->foreign('holiday_type_id')->references('id')->on('holiday_types');
        });

        Schema::table('balances', function (Blueprint $table) {
            $table->foreign('employee_profile_id')->references('id')->on('employee_profiles');
            $table->foreign('holiday_type_id')->references('id')->on('holiday_types');
        });

        Schema::table('contract_products', function (Blueprint $table) {
            $table->foreign('customer_contract_id')->references('id')->on('customer_contracts');
            $table->foreign('tariff_id')->references('id')->on('tariffs');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->foreign('contract_product_id')->references('id')->on('contract_products');
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('address_id')->references('id')->on('addresses');
        });

        Schema::table('meter_addresses', function (Blueprint $table) {
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('meter_id')->references('id')->on('meters');
        });

        Schema::table('employee_contracts', function (Blueprint $table) {
            $table->foreign('employee_profile_id')->references('id')->on('employee_profiles');
        });

        Schema::table('meter_reader_schedules', function (Blueprint $table) {
            $table->foreign('employee_profile_id')->references('id')->on('employee_profiles');
            $table->foreign('meter_id')->references('id')->on('meters');
        });

        Schema::table('customer_contracts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('product_tariffs', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('tariff_id')->references('id')->on('tariffs');
        });

        Schema::table('index_values', function (Blueprint $table) {
            $table->foreign('meter_id')->references('id')->on('meters');
        });

        Schema::table('consumptions', function (Blueprint $table) {
            $table->foreign('prev_index_id')->references('id')->on('index_values');
            $table->foreign('current_index_id')->references('id')->on('index_values');
        });

        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->foreign('consumption_id')->references('id')->on('consumptions');
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('estimations', function (Blueprint $table) {
            $table->foreign('meter_id')->references('id')->on('meters');
        });

        Schema::table('payslips', function (Blueprint $table) {
            $table->foreign('employee_profile_id')->references('id')->on('employee_profiles');
        });
    }

    public function down()
    {
        //
    }
};
