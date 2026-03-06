<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enfant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * Exporte les inscriptions du tableau de bord en CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="dashboard-inscriptions-' . date('Y-m-d-His') . '.csv"',
        ];

        return response()->streamDownload(function () {
            $stream = fopen('php://output', 'w');
            fprintf($stream, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($stream, ['Nom', 'Prénom', 'Date naissance', 'Classe', 'Parent', 'Téléphone parent', 'Statut paiement', 'Séances total', 'Date inscription'], ';');

            Enfant::with('parent')->orderBy('created_at', 'desc')->chunk(500, function ($enfants) use ($stream) {
                foreach ($enfants as $e) {
                    $parent = $e->parent;
                    fputcsv($stream, [
                        $e->nom,
                        $e->prenom,
                        $e->date_naissance?->format('d/m/Y') ?? '',
                        $e->classe ?? '',
                        $parent ? trim($parent->nom . ' ' . $parent->prenom) : '',
                        $parent->telephone ?? '',
                        $e->statut_paiement === 'paye' ? 'Payé' : 'Non payé',
                        $e->nombre_seances_total ?? 0,
                        $e->created_at?->format('d/m/Y H:i') ?? '',
                    ], ';');
                }
            });

            fclose($stream);
        }, 'dashboard-inscriptions-' . date('Y-m-d') . '.csv', $headers);
    }
}
