<?php

namespace App\Exports;

use App\Models\Structure;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Filters\FiltersStructureCeu;
use App\Filters\FiltersStructureCollectivity;
use App\Filters\FiltersStructureSearch;
use App\Filters\FiltersStructureLieu;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;

class StructuresExport implements FromCollection, WithMapping, WithHeadings, ShouldQueue
{
    use Exportable;

    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return QueryBuilder::for(Structure::role($this->role)->with('user'))
            ->allowedFilters([
                'department',
                'state',
                'statut_juridique',
                AllowedFilter::custom('ceu', new FiltersStructureCeu),
                AllowedFilter::custom('lieu', new FiltersStructureLieu),
                AllowedFilter::custom('search', new FiltersStructureSearch),
                AllowedFilter::custom('collectivity', new FiltersStructureCollectivity),
            ])
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'state',
            'response_ratio',
            'response_time',
            'statut_juridique',
            'association_types',
            'structure_publique_type',
            'structure_publique_etat_type',
            'structure_privee_type',
            'siret',
            'description',
            'is_reseau',
            'reseau_id',
            'full_address',
            'address',
            'department',
            'latitude',
            'longitude',
            'zip',
            'city',
            'country',
            'website',
            'instagram',
            'facebook',
            'twitter',
            'created_at',
            'updated_at',
            'user_id',
            // 'user_email',
            // 'user_first_name',
            // 'user_last_name',
        ];
    }

    public function map($structure): array
    {
        $responsable = $structure->user ?? null;

        return [
            $structure->id,
            $structure->name,
            $structure->state,
            $structure->response_ratio,
            $structure->response_time,
            $structure->statut_juridique,
            $structure->association_types,
            $structure->structure_publique_type,
            $structure->structure_publique_etat_type,
            $structure->structure_privee_type,
            $structure->siret,
            $structure->description,
            $structure->is_reseau,
            $structure->reseau_id,
            $structure->full_address,
            $structure->address,
            $structure->department,
            $structure->latitude,
            $structure->longitude,
            $structure->zip,
            $structure->city,
            $structure->country,
            $structure->website,
            $structure->instagram,
            $structure->facebook,
            $structure->twitter,
            $structure->created_at,
            $structure->updated_at,
            $structure->user_id,
            $responsable ? $responsable->email : '',
        ];
    }
}
