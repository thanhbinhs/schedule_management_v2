<?php

namespace Database\Factories;

use App\Models\Professor;
use App\Models\Account; // Import the Account model
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;

class ProfessorFactory extends Factory
{
    protected $model = Professor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Use a Faker instance with the 'vi_VN' locale for Vietnamese names
        $faker = FakerFactory::create('vi_VN');

        // Generate a unique 4-digit number for ProfessorID
        $randomID = '2400' . $this->faker->unique()->numberBetween(1000, 9999);

        // List of specific surnames
        $surnames = ['Lê', 'Nguyễn', 'Trịnh', 'Phạm', 'Vũ'];

        // Optional academic titles
        $academicTitles = ['', 'Ths', 'PGS', 'TS', 'PGS. TS'];

        // Generate a Vietnamese name with a specific surname and optional title
        $surname = $faker->randomElement($surnames);
        $title = $faker->randomElement($academicTitles);
        $name = trim($title . ' ' . $surname . ' ' . $faker->firstName . ' ' . $faker->lastName);

        // Generate a phone number with a specified prefix
        $phonePrefixes = ['03', '09', '08', '02', '04'];
        $phone = $faker->randomElement($phonePrefixes) . $faker->numerify('########'); // Total length: 10 characters

        return [
            'ProfessorID' => $randomID,
            'ProfessorName' => $name, // Generate a Vietnamese name with specific rules
            'ProfessorGmail' => $randomID . '@gmail.com', // Auto-generate email based on ProfessorID
            'ProfessorPhone' => $phone,
            'isLeaderDepartment' => false, // You can change this based on your needs
            'DepartmentID' => 'khoatiengtrung', // Example department
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Professor $professor) {
            // Automatically create an Account for the Professor
            Account::create([
                'username' => $professor->ProfessorID,
                'password' => Hash::make('123456'), // Hash the password
                'role' => 'professor',
            ]);
        });
    }
}
