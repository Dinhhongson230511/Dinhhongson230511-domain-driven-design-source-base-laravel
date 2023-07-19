<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table)
        {
            $table->id();
            $table->string('key');
            $table->string('eligible_user_key')->nullable();
            $table->string('type');
            $table->text('label')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('prefectures')->nullable();
            $table->unsignedInteger('follow_interval')->default(0)
                ->comment('Time (in seconds) SMS will be sent after Email is unread');
            $table->unsignedSmallInteger('status')->default(1);
            $table->text('variables')->nullable();
            $table->string('redirect_to')->nullable()->default(null);
            $table->index('key');
            $table->index('eligible_user_key');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
