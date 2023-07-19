<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationEmailMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_email_messages', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('notification_id')->nullable();
            $table->string('key');
            $table->string('title');
            $table->text('content');
            $table->unsignedSmallInteger('status')->default(1);
            $table->unsignedSmallInteger('is_sms_sent')->default(0);
            $table->dateTime('read_at')->nullable();

            $table->timestamp('created_at')->useCurrent();;
            $table->timestamp('updated_at')->nullable();

            $table->index(['key']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('notification_id')->references('id')->on('notifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_email_messages');
    }
}
