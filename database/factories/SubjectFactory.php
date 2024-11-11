<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Define subjects for each department
        $subjectsByDepartment = [
            'khoacongnghethongtin' => [
                'Lập trình web',
                'Cơ sở dữ liệu',
                'Mạng máy tính',
                'Cấu trúc dữ liệu',
                'Hệ điều hành',
                'An toàn thông tin',
                'Lập trình hướng đối tượng',
                'Phân tích thiết kế hệ thống',
            ],
            'khoatienganh' => [
                'Văn học Anh',
                'Ngữ pháp nâng cao',
                'Giao tiếp tiếng Anh',
                'Dịch thuật',
                'Tiếng Anh thương mại',
                'Kỹ năng viết',
                'Kỹ năng nghe',
            ],
            'khoatiengtrung' => [
                'Ngữ pháp tiếng Trung',
                'Phiên dịch tiếng Trung',
                'Văn hóa Trung Quốc',
                'Tiếng Trung cơ bản',
                'Viết tiếng Trung',
                'Kỹ năng nghe tiếng Trung',
            ],
            'khoatiengnhat' => [
                'Ngữ pháp tiếng Nhật',
                'Phiên dịch tiếng Nhật',
                'Văn hóa Nhật Bản',
                'Tiếng Nhật cơ bản',
                'Viết tiếng Nhật',
                'Kỹ năng nghe tiếng Nhật',
            ],
            'khoatienghan' => [
                'Ngữ pháp tiếng Hàn',
                'Phiên dịch tiếng Hàn',
                'Văn hóa Hàn Quốc',
                'Tiếng Hàn cơ bản',
                'Viết tiếng Hàn',
                'Kỹ năng nghe tiếng Hàn',
                'Kỹ năng giao tiếp tiếng Hàn',
            ],
            'khoakinhte' => [
                'Kinh tế học',
                'Quản trị kinh doanh',
                'Marketing',
                'Tài chính doanh nghiệp',
                'Luật kinh tế',
                'Kế toán',
                'Quản lý nhân sự',
                'Quản trị dự án',
            ],
            'khoacodientu' => [
                'Điện tử cơ bản',
                'Vi xử lý',
                'Điện tử công suất',
                'Điện tử viễn thông',
                'Điện tử công nghiệp',
                'Điện tử số',
                'Điện tử truyền thông',
                'Điện tử công nghệ',
            ],
            'khoaduoc' => [
                'Hóa học',
                'Dược lý học',
                'Dược học',
                'Dược lý',
                'Dược sinh học',
                'Dược học cơ sở',
                'Dược học lâm sàng',
            ]
        ];

        // Randomly select a department
        $departmentID = $this->faker->randomElement(array_keys($subjectsByDepartment));
        // Randomly select a subject name based on the selected department
        $baseSubjectName = $this->faker->randomElement($subjectsByDepartment[$departmentID]);
        // Generate a unique number (e.g., N01, N02, ..., N99)
        $suffixNumber = 'N' . str_pad($this->faker->unique()->numberBetween(1, 99), 2, '0', STR_PAD_LEFT);
        // Combine base subject name with the unique number suffix
        $subjectName = $baseSubjectName . ' ' . $suffixNumber;
        // Randomly assign credits between 1 and 4
        $credits = $this->faker->numberBetween(1, 4);

        return [
            'SubjectName' => $subjectName,
            'DepartmentID' => $departmentID,
            'SubjectCredit' => $credits,
            'SubjectLessons' => $credits * 15,
        ];
    }
}
