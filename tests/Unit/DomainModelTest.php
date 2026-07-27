<?php

namespace Tests\Unit;

use App\Domain\Auth\Models\User;
use App\Domain\CMS\Models\Article;
use App\Domain\Finance\Models\Payment;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Models\BaseModel;
use PHPUnit\Framework\TestCase;

class DomainModelTest extends TestCase
{
    public function test_models_extend_base_model(): void
    {
        $this->assertInstanceOf(BaseModel::class, new Member());
        $this->assertInstanceOf(BaseModel::class, new Payment());
        $this->assertInstanceOf(BaseModel::class, new OrganizationUnit());
        $this->assertInstanceOf(BaseModel::class, new Article());
    }

    public function test_member_model_table_name(): void
    {
        $model = new Member();
        $this->assertSame('members', $model->getTable());
    }

    public function test_payment_model_has_expected_fillable_attributes(): void
    {
        $model = new Payment();
        $fillable = $model->getFillable();

        $this->assertContains('payable_type', $fillable);
        $this->assertContains('payable_id', $fillable);
        $this->assertContains('member_id', $fillable);
        $this->assertContains('amount', $fillable);
        $this->assertContains('status', $fillable);
    }

    public function test_user_model_has_hidden_password_attribute(): void
    {
        $user = new User();
        $hidden = $user->getHidden();

        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }
}
