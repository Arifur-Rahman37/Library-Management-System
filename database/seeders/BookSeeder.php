<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        
        $booksData = [
            // Fiction
            ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'isbn' => '9780061120084', 'total_copies' => 8, 'publisher' => 'HarperCollins', 'pages' => 336],
            ['title' => '1984', 'author' => 'George Orwell', 'isbn' => '9780451524935', 'total_copies' => 10, 'publisher' => 'Signet Classic', 'pages' => 328],
            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'isbn' => '9780141439518', 'total_copies' => 6, 'publisher' => 'Penguin Classics', 'pages' => 432],
            ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'isbn' => '9780743273565', 'total_copies' => 5, 'publisher' => 'Scribner', 'pages' => 180],
            ['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger', 'isbn' => '9780316769480', 'total_copies' => 7, 'publisher' => 'Little, Brown', 'pages' => 277],
            ['title' => 'Moby Dick', 'author' => 'Herman Melville', 'isbn' => '9780142437247', 'total_copies' => 4, 'publisher' => 'Penguin', 'pages' => 720],
            ['title' => 'War and Peace', 'author' => 'Leo Tolstoy', 'isbn' => '9780199232765', 'total_copies' => 3, 'publisher' => 'Oxford Press', 'pages' => 1392],
            ['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'isbn' => '9780547928227', 'total_copies' => 8, 'publisher' => 'Mariner Books', 'pages' => 300],
            
            // Technology
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'isbn' => '9780132350884', 'total_copies' => 7, 'publisher' => 'Prentice Hall', 'pages' => 464],
            ['title' => 'The Pragmatic Programmer', 'author' => 'David Thomas', 'isbn' => '9780201616224', 'total_copies' => 6, 'publisher' => 'Addison-Wesley', 'pages' => 352],
            ['title' => 'Introduction to Algorithms', 'author' => 'Thomas H. Cormen', 'isbn' => '9780262033848', 'total_copies' => 5, 'publisher' => 'MIT Press', 'pages' => 1312],
            ['title' => 'Design Patterns', 'author' => 'Erich Gamma', 'isbn' => '9780201633610', 'total_copies' => 6, 'publisher' => 'Addison-Wesley', 'pages' => 395],
            ['title' => 'The Mythical Man-Month', 'author' => 'Frederick Brooks', 'isbn' => '9780201835953', 'total_copies' => 4, 'publisher' => 'Addison-Wesley', 'pages' => 336],
            ['title' => 'You Dont Know JS', 'author' => 'Kyle Simpson', 'isbn' => '9781491924464', 'total_copies' => 5, 'publisher' => 'O\'Reilly', 'pages' => 500],
            
            // Science
            ['title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'isbn' => '9780553380163', 'total_copies' => 6, 'publisher' => 'Bantam', 'pages' => 256],
            ['title' => 'The Selfish Gene', 'author' => 'Richard Dawkins', 'isbn' => '9780199291151', 'total_copies' => 5, 'publisher' => 'Oxford Press', 'pages' => 384],
            ['title' => 'Cosmos', 'author' => 'Carl Sagan', 'isbn' => '9780345331359', 'total_copies' => 4, 'publisher' => 'Ballantine Books', 'pages' => 384],
            ['title' => 'The Origin of Species', 'author' => 'Charles Darwin', 'isbn' => '9780140439120', 'total_copies' => 5, 'publisher' => 'Penguin', 'pages' => 480],
            ['title' => 'Silent Spring', 'author' => 'Rachel Carson', 'isbn' => '9780618249060', 'total_copies' => 4, 'publisher' => 'Mariner Books', 'pages' => 378],
            
            // Business
            ['title' => 'The Lean Startup', 'author' => 'Eric Ries', 'isbn' => '9780307887894', 'total_copies' => 7, 'publisher' => 'Crown Business', 'pages' => 320],
            ['title' => 'Good to Great', 'author' => 'Jim Collins', 'isbn' => '9780066620992', 'total_copies' => 6, 'publisher' => 'HarperBusiness', 'pages' => 300],
            ['title' => 'The 7 Habits of Highly Effective People', 'author' => 'Stephen Covey', 'isbn' => '9780743269513', 'total_copies' => 8, 'publisher' => 'Free Press', 'pages' => 432],
        ];

        $categoryIds = $categories->pluck('id')->toArray();
        
        // Add initial books
        foreach ($booksData as $index => $bookData) {
            $categoryId = $categoryIds[$index % count($categoryIds)];
            
            Book::create([
                'title' => $bookData['title'],
                'author' => $bookData['author'],
                'isbn' => $bookData['isbn'],
                'category_id' => $categoryId,
                'total_copies' => $bookData['total_copies'],
                'available_copies' => $bookData['total_copies'],
                'publisher' => $bookData['publisher'] ?? 'Unknown',
                'published_year' => rand(1950, 2023),
                'language' => 'English',
                'pages' => $bookData['pages'] ?? rand(150, 800),
                'description' => $bookData['title'] . ' is a must-read book for everyone.',
            ]);
        }
        
        // Generate 180 more books
        $authors = ['J.K. Rowling', 'Stephen King', 'Dan Brown', 'Paulo Coelho', 'J.R.R. Tolkien', 
                   'Agatha Christie', 'Mark Twain', 'Ernest Hemingway', 'Charles Dickens', 'Leo Tolstoy',
                   'Fyodor Dostoevsky', 'Virginia Woolf', 'James Joyce', 'Gabriel Garcia Marquez', 'Toni Morrison'];
        
        $titles = ['The Adventure', 'Mystery of the Lost City', 'The Hidden Truth', 'Eternal Love', 
                  'The Last Journey', 'Dreams and Reality', 'The Silent Voice', 'Beyond the Horizon',
                  'Whispers in the Dark', 'The Golden Age', 'Shadows of Time', 'Echoes of the Past'];
        
        for ($i = 0; $i < 180; $i++) {
            $categoryId = $categoryIds[$i % count($categoryIds)];
            $title = $titles[$i % count($titles)] . ' ' . ($i + 1);
            $author = $authors[$i % count($authors)];
            
            Book::create([
                'title' => $title,
                'author' => $author,
                'isbn' => '978' . str_pad($i + 100000, 10, '0', STR_PAD_LEFT),
                'category_id' => $categoryId,
                'total_copies' => rand(2, 10),
                'available_copies' => rand(1, 10),
                'publisher' => ['Penguin', 'Oxford', 'Cambridge', 'HarperCollins'][rand(0, 3)],
                'published_year' => rand(1950, 2024),
                'language' => 'English',
                'pages' => rand(150, 800),
                'description' => 'This is a wonderful book about ' . $title . ' written by ' . $author,
            ]);
        }
    }
}