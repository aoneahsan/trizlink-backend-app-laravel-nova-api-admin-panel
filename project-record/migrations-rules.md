- for "title" | "name" (user model excluded) for model items we will use "title" as column key and not "name".

- all fields (except relation fields) will be nullable.
- columns name should be camelCase.

- should be part of every migration file
  - $table->string('uniqueId')->nullable();
    $table->unsignedBigInteger('userId');
    $table->boolean('isActive')->default(true);
    $table->integer('sortOrderNo')->default(0)->nullable();
    $table->json('extraAttributes')->nullable();
    $table->softDeletes();

- for relations with other tables, we should always use the "id" (auto incrementing int column) of that table.
