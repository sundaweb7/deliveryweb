<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            if (!Schema::hasColumn('mitras','business_name')) {
                $table->string('business_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('mitras','bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('business_name');
            }
            if (!Schema::hasColumn('mitras','bank_name')) {
                $table->string('bank_name')->nullable()->after('bank_account_number');
            }
            if (!Schema::hasColumn('mitras','bank_account_name')) {
                $table->string('bank_account_name')->nullable()->after('bank_name');
            }
        });

        Schema::table('drivers', function (Blueprint $table) {
            if (!Schema::hasColumn('drivers','bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('wa_contact');
            }
            if (!Schema::hasColumn('drivers','bank_name')) {
                $table->string('bank_name')->nullable()->after('bank_account_number');
            }
            if (!Schema::hasColumn('drivers','bank_account_name')) {
                $table->string('bank_account_name')->nullable()->after('bank_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            if (Schema::hasColumn('mitras','bank_account_name')) $table->dropColumn('bank_account_name');
            if (Schema::hasColumn('mitras','bank_name')) $table->dropColumn('bank_name');
            if (Schema::hasColumn('mitras','bank_account_number')) $table->dropColumn('bank_account_number');
            if (Schema::hasColumn('mitras','business_name')) $table->dropColumn('business_name');
        });

        Schema::table('drivers', function (Blueprint $table) {
            if (Schema::hasColumn('drivers','bank_account_name')) $table->dropColumn('bank_account_name');
            if (Schema::hasColumn('drivers','bank_name')) $table->dropColumn('bank_name');
            if (Schema::hasColumn('drivers','bank_account_number')) $table->dropColumn('bank_account_number');
        });
    }
};