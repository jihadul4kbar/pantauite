# PantauITE - Default Color Configuration

## Primary Color Pattern: Green/Emerald Theme

This document defines the default color scheme used throughout the PantauITE application.

### Primary Colors

| Role | Tailwind Classes | Usage |
|------|-----------------|-------|
| **Primary Gradient** | `from-green-600 via-emerald-600 to-teal-600` | Headers, main buttons, accents |
| **Primary Light** | `from-green-500 to-emerald-600` | Buttons, badges, icons |
| **Primary Background** | `from-slate-50 via-green-50 to-emerald-50` | Page backgrounds |
| **Primary Hover** | `hover:from-green-50 hover:to-emerald-50` | Table row hovers, card hovers |

### Secondary Colors

| Role | Tailwind Classes | Usage |
|------|-----------------|-------|
| **Success/Good** | `from-green-400 to-green-600` | Positive states, deployed assets |
| **Warning/In Progress** | `from-yellow-400 to-orange-600` | Maintenance, pending states |
| **Danger/Error** | `from-red-500 to-red-600` | Expired, critical issues |
| **Neutral** | `from-gray-400 to-gray-600` | Inventory, inactive states |
| **Information** | `from-blue-500 to-blue-600` | Hardware type, info badges |
| **Software Type** | `from-purple-500 to-purple-600` | Software assets/articles |

### Status Badges

| Status | Classes |
|--------|---------|
| Deployed/Active | `bg-gradient-to-r from-green-500 to-emerald-600 text-white` |
| Inventory/Stock | `bg-gradient-to-r from-gray-500 to-gray-600 text-white` |
| Maintenance | `bg-gradient-to-r from-yellow-500 to-orange-600 text-white` |
| Procurement | `bg-gradient-to-r from-blue-500 to-blue-600 text-white` |
| Retired/Disposed | `bg-gradient-to-r from-red-500 to-red-600 text-white` |

### Component Patterns

#### Header Section
```blade
<div class="relative mb-8 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-green-200 rounded-full blur-3xl transform -translate-x-16 translate-y-16"></div>
    </div>
</div>
```

#### Action Buttons
```blade
<a href="..." class="group flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
```

#### Primary Buttons (Gradient)
```blade
<button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-semibold transition-all shadow-md hover:shadow-lg">
```

#### Table Row Hover
```blade
<tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-200 group">
```

#### Icon Container
```blade
<div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
```

### Page Backgrounds

| Page | Background Classes |
|------|-------------------|
| Default | `bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50` |
| Dashboard | `bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50` |
| Tickets | `bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50` |
| Assets | `bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50` |
| Knowledge Base | `bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50` |
| Login | `bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50` |

### Notes

- All gradients use Tailwind's default color palette
- Green/emerald theme represents growth, stability, and success
- Consistent color usage across all modules
- Hover effects use lighter version of primary gradient
- Status indicators maintain semantic meaning (green=good, red=bad, yellow=warning)
