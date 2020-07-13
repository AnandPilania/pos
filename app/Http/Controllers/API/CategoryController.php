<?php


namespace App\Http\Controllers\API;


use App\Http\Models\Category;
use App\Http\Utils\Utils;

class CategoryController
{
    public function getCategoriesWithPageInfo()
    {
        $page_size = request('pageSize');
        $current_page = request('currentPage');
        $order_by = request('orderBy');
        $sort = request('sort');
        $search = request('search');
        $user = request('user');

        $where_clause = [];
        if (isset($search) && $search != "") {
            $where_clause[] = ['name', 'like', "%$search%"];
            $where_clause[] = ['name_second', 'like', "%$search%"];
        }

        $total_count = Category::where('customer_id', $user->id)
            ->where(function ($query) use ($where_clause) {
                if (count($where_clause) > 0) {
                    $query->where([$where_clause[0]]);
                    for ($i = 1; $i < count($where_clause); $i++) {
                        $query->orwhere([$where_clause[$i]]);
                    }
                }
            })
            ->count();

        $page_size = (int)$page_size;
        $current_page = (int)$current_page;
        if ($page_size < 1)
            $page_size = 10;

        if ($current_page < 1)
            $current_page = 1;

        $total_page = ceil($total_count / $page_size);

        if ($sort == "" || $sort != "asc" || $sort != "desc") {
            $sort = "asc";
        }

        if ($order_by == "") {
            $order_by = "id";
        } else if ($order_by == "status") {
            $order_by = "active";
            $sort = "desc";
        } else if ($order_by != "name") {
            $order_by = "id";
        }

        $categories = Category::where('customer_id', $user->id)
            ->where(function ($query) use ($where_clause) {
                if (count($where_clause) > 0) {
                    $query->where([$where_clause[0]]);
                    for ($i = 1; $i < count($where_clause); $i++) {
                        $query->orwhere([$where_clause[$i]]);
                    }
                }
            })
            ->offset($page_size * ($current_page - 1))
            ->limit($page_size)
            ->orderBy($order_by, $sort)
            ->get();

        return Utils::makeResponse([
            'currentPage' => $current_page,
            'pageSize' => $page_size,
            'totalItem' => $total_count,
            'totalPage' => $total_page,
            'data' => $categories,
            'status' => true
        ]);
    }

    public function getAllCategoryList()
    {
        $user = request('user');

        $categories = Category::where([
            ['customer_id', $user->id],
            ['active', 1],
        ])
            ->select('id as value', 'name as label', 'id as key')
            ->get();

        return Utils::makeResponse([
            'category_list' => $categories,
        ]);
    }

    public function getCategoryInfo()
    {
        $category_id = request('categoryId');
        $user = request('user');

        if (!isset($category_id)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Category::where([
            ['id', $category_id],
            ['customer_id', $user->id]
        ])
            ->first();

        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = $category->setHidden([
            'customer_id'
        ]);

        return Utils::makeResponse([
            'category' => $category
        ]);
    }

    public function addCategory()
    {
        $user = request('user');
        $name = request('category-name');
        $status = request('status');

        request()->validate([
            'category-name' => 'required',
        ]);

        $category = new Category();
        $category->customer_id = $user->id;
        $category->name = $name;
        $category->active = $status;

        $category->save();

        return Utils::makeResponse();
    }

    public function updateCategory()
    {
        $id = request('id');
        $name = request('name');
        $status = request('state');
        $user = request('user');

        $validation = Validator::make(request()->all(), [
            'name' => 'required',
        ]);

        if ($validation->fails()) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Category::where([
            ['id', $id],
            ['customer_id', $user->id]
        ])
            ->first();
        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Category::where('id', $id)->update([
            'name' => $name,
            'active' => $status
        ]);
        return Utils::makeResponse();
    }

    public function toggleActiveCategory()
    {
        $category_id = request('categoryId');
        $user = request('user');

        if (!isset($category_id)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Category::where([
            ['id', $category_id],
            ['customer_id', $user->id]
        ])
            ->first();

        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $cu = Category::find($category_id);
        $cu->active = 1 - $cu->active;
        $cu->save();

        return Utils::makeResponse();
    }

    public function changeCategoriesState()
    {
        $user = request('user');
        $category_ids = request('categoryIds');
        $state = request('state');

        $category_ids = json_decode($category_ids);

        if (!isset($category_ids) || !isset($state) || count($category_ids) < 1 || ($state != 0 && $state != 1)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Category::where('customer_id', $user->id)
            ->whereIn('id', $category_ids)
            ->get();

        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Category::where('customer_id', $user->id)
            ->whereIn('id', $category_ids)
            ->update([
                'active' => $state
            ]);

        return Utils::makeResponse();
    }

    public function toggleCategoryAllVisible()
    {
        $user = request('user');
        Category::where('customer_id', $user->id)->update(['active' => 1]);
        return Utils::makeResponse();
    }

    public function toggleCategoryAllInvisible()
    {
        $user = request('user');
        Category::where('customer_id', $user->id)->update(['active' => 0]);
        return Utils::makeResponse();
    }

    public function deleteCategories()
    {
        $user = request('user');
        $category_ids = request('categoryIds');

        $category_ids = json_decode($category_ids);

        if (!isset($category_ids) || count($category_ids) < 1) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Category::where('customer_id', $user->id)
            ->whereIn('id', $category_ids)
            ->get();

        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Category::where('customer_id', $user->id)
            ->whereIn('id', $category_ids)
            ->delete();

        return Utils::makeResponse();
    }
}
