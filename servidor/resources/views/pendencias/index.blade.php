@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <h2 class="text-3xl font-semibold text-gray-800">📋 Lista de Pendências</h2>
        <p class="text-sm text-gray-500">Aprovação manual dos dados alterados</p>
    </div>

    @forelse($pendencias as $pendencia)
        <div class="bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
            <div class="mb-4">
                <span class="inline-block text-sm px-3 py-1 bg-gray-200 text-gray-700 rounded-full mr-2">
                    <strong class="text-green-700">Tabela:</strong> {{ $pendencia->tabela }}
                </span>
                <span class="inline-block text-sm px-3 py-1 bg-gray-200 text-gray-700 rounded-full">
                    <strong>ID:</strong> {{ $pendencia->chave_primaria }}
                </span>
                <p class="text-xs text-gray-400 mt-1">Recebido em {{ \Carbon\Carbon::parse($pendencia->created_at)->format('d/m/Y H:i') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">🕘 Antes</h3>
                    <div class="bg-gray-100 text-xs p-3 rounded-md max-h-96 overflow-y-auto whitespace-pre-wrap font-mono">
                        {{ json_encode(json_decode($pendencia->dados_antigos, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">🆕 Depois</h3>
                    <div class="bg-gray-100 text-xs p-3 rounded-md max-h-96 overflow-y-auto whitespace-pre-wrap font-mono">
                        {{ json_encode(json_decode($pendencia->dados_novos, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-6">
                <form method="POST" action="{{ route('pendencias.rejeitar', $pendencia->id) }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded shadow-sm transition">
                        <i class="ti ti-x"></i> Rejeitar
                    </button>
                </form>

                <form method="POST" action="{{ route('pendencias.aprovar', $pendencia->id) }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded shadow-sm transition">
                        <i class="ti ti-check"></i> Aprovar
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center py-16">
            <h3 class="text-lg font-medium text-gray-600">🎉 Tudo limpo por aqui!</h3>
            <p class="text-sm text-gray-400">Nenhuma pendência aguardando aprovação.</p>
        </div>
    @endforelse
</div>
@endsection
