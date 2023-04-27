<?php //0c0a02f4e6226a78d889aecfd7281287
/** @noinspection all */

namespace App\Events {

    use App\Models\Pedido;
    use Illuminate\Broadcasting\PendingBroadcast;
    
    /**
     * @method static void dispatch($pago)
     * @method static PendingBroadcast broadcast($pago)
     */
    class PagoEvent {}
    
    /**
     * @method static void dispatch(Pedido $pedido)
     * @method static PendingBroadcast broadcast(Pedido $pedido)
     */
    class PedidoAnulledEvent {}
    
    /**
     * @method static void dispatch($pedido)
     * @method static PendingBroadcast broadcast($pedido)
     */
    class PedidoAtendidoEvent {}
    
    /**
     * @method static void dispatch($pedido)
     * @method static PendingBroadcast broadcast($pedido)
     */
    class PedidoEntregadoEvent {}
    
    /**
     * @method static void dispatch($pedido)
     * @method static PendingBroadcast broadcast($pedido)
     */
    class PedidoEvent {}
}

namespace App\Jobs {

    use Illuminate\Foundation\Bus\PendingDispatch;
    
    /**
     * @method static PendingDispatch dispatch($client_id)
     * @method static mixed dispatchNow($client_id)
     * @method static mixed dispatchSync($client_id)
     */
    class PostCreatePedido {}
    
    /**
     * @method static PendingDispatch dispatch($cliente_id)
     * @method static mixed dispatchNow($cliente_id)
     * @method static mixed dispatchSync($cliente_id)
     */
    class PostCreatePedidoClienteUltimoPedido {}
    
    /**
     * @method static PendingDispatch dispatch($codigo)
     * @method static mixed dispatchNow($codigo)
     * @method static mixed dispatchSync($codigo)
     */
    class PostUpdatePedido {}
    
    /**
     * @method static PendingDispatch dispatch($client_id)
     * @method static mixed dispatchNow($client_id)
     * @method static mixed dispatchSync($client_id)
     */
    class PostUpdateSituacion {}
    
    /**
     * @method static PendingDispatch dispatch($direccionGrupoId)
     * @method static mixed dispatchNow($direccionGrupoId)
     * @method static mixed dispatchSync($direccionGrupoId)
     */
    class SyncOlvaJob {}
}

namespace Illuminate\Foundation\Console {

    use Illuminate\Foundation\Bus\PendingDispatch;
    
    /**
     * @method static PendingDispatch dispatch(array $data)
     * @method static mixed dispatchNow(array $data)
     * @method static mixed dispatchSync(array $data)
     */
    class QueuedCommand {}
}

namespace Illuminate\Queue {

    use Illuminate\Foundation\Bus\PendingDispatch;
    use Laravel\SerializableClosure\SerializableClosure;
    
    /**
     * @method static PendingDispatch dispatch(SerializableClosure $closure)
     * @method static mixed dispatchNow(SerializableClosure $closure)
     * @method static mixed dispatchSync(SerializableClosure $closure)
     */
    class CallQueuedClosure {}
}

namespace Laravel\Fortify\Events {

    use App\Models\User;
    use Illuminate\Broadcasting\PendingBroadcast;
    
    /**
     * @method static void dispatch(User $user)
     * @method static PendingBroadcast broadcast(User $user)
     */
    class RecoveryCodesGenerated {}
}

namespace Laravel\Jetstream\Events {

    use Illuminate\Broadcasting\PendingBroadcast;
    
    /**
     * @method static void dispatch($owner)
     * @method static PendingBroadcast broadcast($owner)
     */
    class AddingTeam {}
    
    /**
     * @method static void dispatch($team, $user)
     * @method static PendingBroadcast broadcast($team, $user)
     */
    class AddingTeamMember {}
    
    /**
     * @method static void dispatch($team, $email, $role)
     * @method static PendingBroadcast broadcast($team, $email, $role)
     */
    class InvitingTeamMember {}
    
    /**
     * @method static void dispatch($team, $user)
     * @method static PendingBroadcast broadcast($team, $user)
     */
    class RemovingTeamMember {}
    
    /**
     * @method static void dispatch($team, $user)
     * @method static PendingBroadcast broadcast($team, $user)
     */
    class TeamMemberAdded {}
    
    /**
     * @method static void dispatch($team, $user)
     * @method static PendingBroadcast broadcast($team, $user)
     */
    class TeamMemberRemoved {}
    
    /**
     * @method static void dispatch($team, $user)
     * @method static PendingBroadcast broadcast($team, $user)
     */
    class TeamMemberUpdated {}
}

namespace Maatwebsite\Excel\Jobs {

    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\PendingDispatch;
    use Maatwebsite\Excel\Concerns\FromQuery;
    use Maatwebsite\Excel\Concerns\FromView;
    use Maatwebsite\Excel\Files\TemporaryFile;
    
    /**
     * @method static PendingDispatch dispatch(object $sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex, array $data)
     * @method static mixed dispatchNow(object $sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex, array $data)
     * @method static mixed dispatchSync(object $sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex, array $data)
     */
    class AppendDataToSheet {}
    
    /**
     * @method static PendingDispatch dispatch(FromQuery $sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex, int $page, int $chunkSize)
     * @method static mixed dispatchNow(FromQuery $sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex, int $page, int $chunkSize)
     * @method static mixed dispatchSync(FromQuery $sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex, int $page, int $chunkSize)
     */
    class AppendQueryToSheet {}
    
    /**
     * @method static PendingDispatch dispatch(FromView $sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex)
     * @method static mixed dispatchNow(FromView $sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex)
     * @method static mixed dispatchSync(FromView $sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex)
     */
    class AppendViewToSheet {}
    
    /**
     * @method static PendingDispatch dispatch(object $export, TemporaryFile $temporaryFile, string $writerType)
     * @method static mixed dispatchNow(object $export, TemporaryFile $temporaryFile, string $writerType)
     * @method static mixed dispatchSync(object $export, TemporaryFile $temporaryFile, string $writerType)
     */
    class QueueExport {}
    
    /**
     * @method static PendingDispatch dispatch(ShouldQueue $import = null)
     * @method static mixed dispatchNow(ShouldQueue $import = null)
     * @method static mixed dispatchSync(ShouldQueue $import = null)
     */
    class QueueImport {}
}
