<?php

namespace App\Rules;

use App\Models\Attribute;
use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Request;

class UniqueAttributeInHierarchy implements ValidationRule
{
    private $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $categoryId = Request::get('category_id');
        if (!$categoryId) {
            return; // Let the 'required' rule handle this
        }

        $category = Category::find($categoryId);
        if (!$category) {
            return; // Let the 'exists' rule handle this
        }

        // 1. Get all ancestor and descendant IDs
        $parentIds = $category->getAllParentIds();
        $childIds = $category->getAllChildIds();

        // 2. Combine them all into one list of IDs to check
        $hierarchyIds = array_merge([$categoryId], $parentIds, $childIds);

        // 3. Check if an attribute with the same name exists in this hierarchy
        $query = Attribute::where('name', $value)
                          ->whereIn('category_id', $hierarchyIds);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail('The attribute name ":input" already exists in this category\'s parent or child hierarchy.');
        }
    }
}