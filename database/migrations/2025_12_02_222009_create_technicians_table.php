    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('technicians', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->unique()->nullable();
                $table->string('role')->default('technician');
                $table->string('email')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->text('area_coverage')->nullable();
                $table->unsignedInteger('gaji_pokok')->default(0);
                $table->timestamp('join_date')->useCurrent();
                $table->timestamp('last_login')->nullable();
                $table->string('whatsapp_group_id')->nullable();

                $table->time('check_in_limit')->nullable();

               
                // Rentang bonus check out
                $table->time('bonus_check_out_mulai')->nullable();
                $table->time('bonus_check_out_selesai')->nullable();

                
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('technicians');
        }
    };
