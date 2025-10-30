<?php
namespace Database\Factories;

// Pastikan meng-import model yang dibutuhkan
use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Tentukan tipe transaksi secara acak
        $type = $this->faker->randomElement(['debit', 'credit']);

        // Dapatkan kategori yang sesuai dengan tipe
        // Jika tidak ada, buat baru
        $category = Category::factory()->create(['type' => ($type == 'debit' ? 'income' : 'expense')]);

        return [
            // Gunakan factory lain untuk membuat relasi otomatis
            'account_id' => Account::factory(),
            'category_id' => $category->id,
            'user_id' => User::factory(),
            'description' => $this->faker->sentence(4), // Deskripsi singkat
            'amount' => $this->faker->numberBetween(10000, 1000000), // Jumlah acak
            'type' => $type, // Tipe debit/credit
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'), // Tanggal acak
        ];
    }
}
