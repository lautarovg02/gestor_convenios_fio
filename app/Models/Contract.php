<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = ['signing_date', 'url_certificate_afip', 'url_statute', 'url_assignment_authorities', 'company_id', 'secretary_id', 'teacher_id', 'creation_date', 'contact_employee_id', 'representative_employee_id', 'rector', 'contract_status_id', 'type_framework_agreement_id', 'file'];

    //Relación  1:n con Company
    public function company(): BelongsTo {
        return $this->belongsTo(Company::class, 'company_id');
    }

    //Relación  1:n con Secretary
    public function secretary(): BelongsTo {
        return $this->belongsTo(Secretary::class, 'secretary_id');
    }

    //Relación  1:n con Teacher
    public function teacher(): BelongsTo {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    //Relación  1:n con Employee
    public function contactEmployee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'contact_employee_id');
    }
    
     //Relación  0:n con Employee
     public function representativeEmployee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'representative_employee_id');
    }

     //Relación  1:n con Teacher(rector)
     public function rectorTeacher(): BelongsTo {
        return $this->belongsTo(Teacher::class, 'rector');
    }


     //Relación  1:n con ContractStatus
     public function status(): BelongsTo {
        return $this->belongsTo(ContractStatus::class, 'contract_status_id');
    }

     //Relación  1:n con typeFrameworkAgreement
     public function typeFrameworkAgreement(): BelongsTo {
        return $this->belongsTo(TypeFrameworkAgreement::class, 'type_framework_agreement_id');
    }
    
    public function specifics()
    {
        return $this->hasMany(Specific::class);
    }
    public function specificResidenceAgreements()
    {
        return $this->hasMany(SpecificResidenceAgreement::class);
    }
    public function individualIntershipAgreements()
    {
        return $this->hasMany(IndividualInternshipAgreement::class);
    }
}
