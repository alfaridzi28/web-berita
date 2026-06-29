<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat kolom temporary tipe JSON
        Schema::table('posts', function (Blueprint $table) {
            $table->json('title_json')->nullable();
            $table->json('content_json')->nullable();
            $table->json('meta_description_json')->nullable();
        });

        // 2. Migrasikan data string/text ke JSON {"id": "value"}
        $posts = DB::table('posts')->get();
        foreach ($posts as $post) {
            DB::table('posts')->where('id', $post->id)->update([
                'title_json'            => json_encode(['id' => $post->title]),
                'content_json'          => json_encode(['id' => $post->content]),
                'meta_description_json' => json_encode(['id' => $post->meta_description]),
            ]);
        }

        // 3. Hapus kolom lama
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['title', 'content', 'meta_description']);
        });

        // 4. Rename kolom JSON ke nama asli
        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('title_json', 'title');
            $table->renameColumn('content_json', 'content');
            $table->renameColumn('meta_description_json', 'meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Proses kebalikan: JSON ke Text
        Schema::table('posts', function (Blueprint $table) {
            $table->text('title_text')->nullable();
            $table->longText('content_text')->nullable();
            $table->text('meta_description_text')->nullable();
        });

        $posts = DB::table('posts')->get();
        foreach ($posts as $post) {
            $title = json_decode($post->title, true)['id'] ?? '';
            $content = json_decode($post->content, true)['id'] ?? '';
            $meta = json_decode($post->meta_description, true)['id'] ?? '';

            DB::table('posts')->where('id', $post->id)->update([
                'title_text'            => $title,
                'content_text'          => $content,
                'meta_description_text' => $meta,
            ]);
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['title', 'content', 'meta_description']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('title_text', 'title');
            $table->renameColumn('content_text', 'content');
            $table->renameColumn('meta_description_text', 'meta_description');
        });
    }
};
