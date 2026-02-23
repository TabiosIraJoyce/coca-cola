<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-bold text-xl text-black-800 leading-tight">

            <?php echo e(__('Receipts Breakdown')); ?>

            
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="overflow-x-auto w-full">
    <table class="min-w-full table-fixed border-collapse text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-2 py-2 w-20">ID</th>
                <th class="px-2 py-2 w-40">Route</th>
                <th class="px-2 py-2 w-32">Leadman</th>
                <th class="px-2 py-2 w-20">FC</th>
                <th class="px-2 py-2 w-20">HC</th>
                <th class="px-2 py-2 w-20">Box</th>
                <th class="px-2 py-2 w-24">Total Cases</th>
                <th class="px-2 py-2 w-24">Total UCS</th>
                <th class="px-2 py-2 w-32">No. of Receipts</th>
                <th class="px-2 py-2 w-32">Customer Count</th>
                <th class="px-2 py-2 w-28">Gross Sales</th>
                <th class="px-2 py-2 w-32">Sales Discounts</th>
                <th class="px-2 py-2 w-32">Coupon Discount</th>
                <th class="px-2 py-2 w-28">Net Sales</th>
                <th class="px-2 py-2 w-32">Containers Deposit</th>
                <th class="px-2 py-2 w-32">Purchased Refund</th>
                <th class="px-2 py-2 w-28">Stock Transfer</th>
                <th class="px-2 py-2 w-32">Net Credit Sales</th>
                <th class="px-2 py-2 w-32">Shortage Collections</th>
                <th class="px-2 py-2 w-32">AR Collections</th>
                <th class="px-2 py-2 w-28">Other Income</th>
                <th class="px-2 py-2 w-28">Cash Proceeds</th>

                <!-- Mode of Remittance -->
                <th class="px-2 py-2 w-28 bg-gray-200">Remit (Cash)</th>
                <th class="px-2 py-2 w-28 bg-gray-200">Remit (Check)</th>

                <!-- Final columns -->
                <th class="px-2 py-2 w-28">Total Remittance</th>
                <th class="px-2 py-2 w-28">Shortage / Overage</th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            <?php $__currentLoopData = $receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="px-2 py-1 break-words"><?php echo e($receipt->id); ?></td>
                <td class="px-2 py-1 break-words"><?php echo e($receipt->validatedRemittance->route ?? '-'); ?></td>
                <td class="px-2 py-1 break-words"><?php echo e($receipt->validatedRemittance->leadman ?? '-'); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->fc ?? '-'); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->hc ?? '-'); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->box ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->total_cases ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->total_ucs ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->no_of_receipts ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->customer_counts ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->gross_sales ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->sales_discounts ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->coupon_discount ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->net_sales ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->containers_deposit ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->purchased_refund ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->stock_transfer ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->net_credit_sales ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->shortage_collections ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->ar_collections ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->other_income ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->cash_proceeds ?? 0); ?></td>

                <!-- Cash & Check -->
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->remit_cash ?? 0); ?></td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->remit_check ?? 0); ?></td>

                <!-- Totals -->
                <td class="px-2 py-1">
                    <?php echo e(($receipt->validatedRemittance->remit_cash ?? 0) + ($receipt->validatedRemittance->remit_check ?? 0)); ?>

                </td>
                <td class="px-2 py-1"><?php echo e($receipt->validatedRemittance->shortover ?? 0); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
</div>
</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\receipts-breakdown.blade.php ENDPATH**/ ?>