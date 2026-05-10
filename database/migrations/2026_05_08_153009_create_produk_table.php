public function up(): void
{
    Schema::create('produk', function (Blueprint $table) {

        $table->id();

        $table->foreignId('kategori_id');

        $table->string('nama_produk');

        $table->integer('harga');

        $table->integer('stok');

        $table->integer('berat');

        $table->text('detail');

        $table->string('foto');

        $table->tinyInteger('status')->default(1);

        $table->timestamps();

    });
}